<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Porpaginas\Result;

/**
 * @author Marco Pivetta <ocramius@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMBatchProcessor implements \IteratorAggregate, \Countable
{
    private $result;
    private $em;
    private $batchSize;

    public function __construct(Result $result, EntityManagerInterface $em, int $batchSize = 100)
    {
        $this->result = $result;
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
            foreach ($this->result as $key => $value) {
                if (\is_array($value)) {
                    $firstKey = \key($value);

                    if (null !== $firstKey && \is_object($value[$firstKey]) && $value === [$firstKey => $value[$firstKey]]) {
                        $value = $value[$firstKey];
                    }
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

    public function count(): int
    {
        return $this->result->count();
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
