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
    private $results;
    private $em;
    private $batchSize;

    public function __construct(iterable $results, EntityManagerInterface $em, int $batchSize = 100)
    {
        $this->results = $results;
        $this->em = $em;
        $this->batchSize = $batchSize;
    }

    public function getIterator(): \Traversable
    {
        $logger = $this->em->getConfiguration()->getSQLLogger();
        $this->em->getConfiguration()->setSQLLogger(null);
        $this->em->beginTransaction();

        $iteration = 0;

        try {
            foreach ($this->results as $key => $value) {
                if ($this->results instanceof IterableResult) {
                    $value = IterableQueryResultNormalizer::normalize($value);
                }

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
        if ($iteration % $this->batchSize) {
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
