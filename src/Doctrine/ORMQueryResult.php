<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

final class ORMQueryResult implements Result
{
    private $query;
    private $fetchCollection;
    private $count;

    /**
     * @param Query|QueryBuilder $query
     */
    public function __construct($query, bool $fetchCollection = true)
    {
        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        $this->query = $query;
        $this->fetchCollection = $fetchCollection;
    }

    public function take(int $offset, int $limit): Page
    {
        return new CallbackPage(
            function ($offset, $limit) {
                return \iterator_to_array($this->createPaginator($offset, $limit));
            },
            [$this, 'count'],
            $offset,
            $limit
        );
    }

    public function count(): int
    {
        if (null !== $this->count) {
            return $this->count;
        }

        return $this->count = \count(new Paginator($this->query, $this->fetchCollection));
    }

    public function getIterator(): iterable
    {
        foreach ($this->query->iterate() as $row) {
            yield $row[0];

            $this->query->getEntityManager()->detach($row[0]);
        }
    }

    public function batchIterator(int $batchSize = 100): iterable
    {
        if (!\class_exists(SimpleBatchIteratorAggregate::class)) {
            throw new \RuntimeException('To enable batch processing, you must install "ocramius/doctrine-batch-utils": composer require ocramius/doctrine-batch-utils');
        }

        $this->count = null; // reset count as entities may have been removed

        $logger = $this->query->getEntityManager()->getConfiguration()->getSQLLogger();
        $this->query->getEntityManager()->getConfiguration()->setSQLLogger(null);

        yield from SimpleBatchIteratorAggregate::fromQuery($this->query, $batchSize);

        $this->query->getEntityManager()->getConfiguration()->setSQLLogger($logger);
    }

    private function createPaginator(int $offset, int $limit): Paginator
    {
        $query = clone $this->query;
        $query->setParameters($this->query->getParameters());

        foreach ($this->query->getHints() as $name => $value) {
            $query->setHint($name, $value);
        }

        $query->setFirstResult($offset)->setMaxResults($limit);

        return new Paginator($query, $this->fetchCollection);
    }
}
