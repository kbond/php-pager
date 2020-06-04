<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\IterableResult;

/**
 * @author Marco Pivetta <ocramius@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMBatchProcessor implements \IteratorAggregate
{
    private iterable $items;
    private EntityManagerInterface $em;
    private int $chunkSize;

    public function __construct(iterable $items, EntityManagerInterface $em, int $chunkSize = 100)
    {
        if ($items instanceof IterableResult) {
            $items = new ORMIterableResultDecorator($items);
        }

        $this->items = $items;
        $this->em = $em;
        $this->chunkSize = $chunkSize;
    }

    public function getIterator(): \Traversable
    {
        $logger = $this->em->getConfiguration()->getSQLLogger();
        $this->em->getConfiguration()->setSQLLogger(null);
        $this->em->beginTransaction();

        $iteration = 0;

        try {
            foreach ($this->items as $key => $value) {
                yield $key => $value;

                $this->flushAndClearBatch(++$iteration);
            }
        } catch (\Throwable $exception) {
            $this->em->rollback();

            throw $exception;
        }

        $this->flushAndClear();
        $this->em->commit();
        $this->em->getConfiguration()->setSQLLogger($logger);
    }

    private function flushAndClearBatch(int $iteration): void
    {
        if ($iteration % $this->chunkSize) {
            return;
        }

        $this->flushAndClear();
    }

    private function flushAndClear(): void
    {
        $this->em->flush();
        $this->em->clear();
    }
}
