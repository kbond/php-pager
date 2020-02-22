<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMBatchProcessor implements \IteratorAggregate, \Countable
{
    private $query;
    private $batchSize;
    private $count;

    public function __construct(Query $query, int $batchSize, int $count)
    {
        if (!\class_exists(SimpleBatchIteratorAggregate::class)) {
            throw new \RuntimeException('To enable batch processing, you must install "ocramius/doctrine-batch-utils": composer require ocramius/doctrine-batch-utils');
        }

        $this->query = $query;
        $this->batchSize = $batchSize;
        $this->count = $count;
    }

    public function getIterator(): \Traversable
    {
        $logger = $this->query->getEntityManager()->getConfiguration()->getSQLLogger();
        $this->query->getEntityManager()->getConfiguration()->setSQLLogger(null);

        yield from SimpleBatchIteratorAggregate::fromQuery($this->query, $this->batchSize);

        $this->query->getEntityManager()->getConfiguration()->setSQLLogger($logger);
    }

    public function count(): int
    {
        return $this->count;
    }
}
