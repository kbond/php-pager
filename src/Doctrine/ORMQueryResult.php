<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

final class ORMQueryResult implements Result
{
    private $query;
    private $fetchCollection;
    private $useOutputWalkers;
    private $count;

    /**
     * @param Query|QueryBuilder $query
     * @param bool|null          $useOutputWalkers Set to false if query contains only columns
     */
    public function __construct($query, bool $fetchCollection = true, $useOutputWalkers = null)
    {
        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        $this->query = $query;
        $this->fetchCollection = $fetchCollection;
        $this->useOutputWalkers = $useOutputWalkers;
    }

    public function take(int $offset, int $limit): Page
    {
        return new CallbackPage(
            function ($offset, $limit) {
                return \iterator_to_array($this->paginatorFor(
                    $this->cloneQuery()->setFirstResult($offset)->setMaxResults($limit)
                ));
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

        return $this->count = \count($this->paginatorFor($this->cloneQuery()));
    }

    public function getIterator(): \Traversable
    {
        $query = $this->cloneQuery();
        $logger = $query->getEntityManager()->getConfiguration()->getSQLLogger();
        $query->getEntityManager()->getConfiguration()->setSQLLogger(null);

        foreach ($this->cloneQuery()->iterate() as $key => $value) {
            yield $key => IterableQueryResultNormalizer::normalize($value);

            $query->getEntityManager()->clear();
        }

        $query->getEntityManager()->getConfiguration()->setSQLLogger($logger);
    }

    public function batchIterator(int $batchSize = 100): ORMCountableBatchProcessor
    {
        return new ORMCountableBatchProcessor(
            new class($this->cloneQuery(), $this) implements \IteratorAggregate, \Countable {
                private $query;
                private $result;

                public function __construct(Query $query, ORMQueryResult $result)
                {
                    $this->query = $query;
                    $this->result = $result;
                }

                public function getIterator(): \Traversable
                {
                    return $this->query->iterate();
                }

                public function count(): int
                {
                    return $this->result->count();
                }
            },
            $this->query->getEntityManager(),
            $batchSize
        );
    }

    private function paginatorFor(Query $query): Paginator
    {
        return (new Paginator($query, $this->fetchCollection))->setUseOutputWalkers($this->useOutputWalkers);
    }

    private function cloneQuery(): Query
    {
        $query = clone $this->query;
        $query->setParameters($this->query->getParameters());

        foreach ($this->query->getHints() as $name => $value) {
            $query->setHint($name, $value);
        }

        return $query;
    }
}
