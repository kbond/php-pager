<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zenstruck\Porpaginas\Arrays\ArrayPage;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\JsonSerializableIterator;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

final class ORMQueryResult implements Result
{
    use JsonSerializableIterator;

    private $query;
    private $fetchCollection;
    private $count;
    private $result;

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
        if (null !== $this->result) {
            return new ArrayPage(
                \array_slice($this->result, $offset, $limit),
                $offset,
                $limit,
                \count($this->result)
            );
        }

        $results = function ($offset, $limit) {
            return \iterator_to_array($this->createPaginator($offset, $limit));
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    public function count(): int
    {
        if (null !== $this->count) {
            return $this->count;
        }

        return $this->count = \count($this->createPaginator(0, 1));
    }

    public function getIterator(): \Iterator
    {
        if (null === $this->result) {
            $this->result = $this->query->execute();
            $this->count = \count($this->result);
        }

        return new \ArrayIterator($this->result);
    }

    public function getQuery(): Query
    {
        return $this->query;
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
