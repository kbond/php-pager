<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMBatchProcessor implements \IteratorAggregate, \Countable
{
    private $result;
    private $em;
    private $batchSize;

    public function __construct(Result $result, EntityManagerInterface $em, int $batchSize = 100)
    {
        if (!\class_exists(SimpleBatchIteratorAggregate::class)) {
            throw new \RuntimeException('To enable batch processing, you must install "ocramius/doctrine-batch-utils": composer require ocramius/doctrine-batch-utils');
        }

        $this->result = $result;
        $this->em = $em;
        $this->batchSize = $batchSize;
    }

    public function getIterator(): \Traversable
    {
        $logger = $this->em->getConfiguration()->getSQLLogger();
        $this->em->getConfiguration()->setSQLLogger(null);

        yield from SimpleBatchIteratorAggregate::fromTraversableResult($this->result, $this->em, $this->batchSize);

        $this->em->getConfiguration()->setSQLLogger($logger);
    }

    public function count(): int
    {
        return $this->result->count();
    }
}
