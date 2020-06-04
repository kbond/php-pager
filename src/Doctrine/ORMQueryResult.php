<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMCountableBatchProcessor;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMIterableResultDecorator;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

final class ORMQueryResult implements Result
{
    private Query $query;
    private bool $fetchCollection;
    private ?bool $useOutputWalkers;
    private ?int $count = null;

    /**
     * @param Query|QueryBuilder $query
     */
    public function __construct($query, bool $fetchCollection = true, ?bool $useOutputWalkers = null)
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
            function($offset, $limit) {
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
        return $this->batchIterator();
    }

    public function batchIterator(int $chunkSize = 100): \Traversable
    {
        $iteration = 0;

        foreach (new ORMIterableResultDecorator($this->cloneQuery()->iterate()) as $key => $value) {
            yield $key => $value;

            if (++$iteration % $chunkSize) {
                continue;
            }

            $this->query->getEntityManager()->clear();
        }

        $this->query->getEntityManager()->clear();
    }

    public function batchProcessor(int $chunkSize = 100): ORMCountableBatchProcessor
    {
        return new ORMCountableBatchProcessor(
            new class($this->cloneQuery(), $this) implements \IteratorAggregate, \Countable {
                private Query $query;
                private Result $result;

                public function __construct(Query $query, Result $result)
                {
                    $this->query = $query;
                    $this->result = $result;
                }

                public function getIterator(): \Traversable
                {
                    return new ORMIterableResultDecorator($this->query->iterate());
                }

                public function count(): int
                {
                    return $this->result->count();
                }
            },
            $this->query->getEntityManager(),
            $chunkSize
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
