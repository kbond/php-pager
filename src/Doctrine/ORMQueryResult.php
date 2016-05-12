<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zenstruck\Porpaginas\Arrays\ArrayPage;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Result;

final class ORMQueryResult implements Result
{
    private $query;
    private $fetchCollection;
    private $count;
    private $result;

    /**
     * @param Query|QueryBuilder $query
     * @param bool               $fetchCollection
     */
    public function __construct($query, $fetchCollection = true)
    {
        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        $this->query = $query;
        $this->fetchCollection = $fetchCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        if ($this->result !== null) {
            return new ArrayPage(
                array_slice($this->result, $offset, $limit),
                $offset,
                $limit,
                count($this->result)
            );
        }

        $results = function ($offset, $limit) {
            return iterator_to_array($this->createPaginator($offset, $limit));
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (null !== $this->count) {
            return $this->count;
        }

        return $this->count = count($this->createPaginator(0, 1));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (null === $this->result) {
            $this->result = $this->query->execute();
            $this->count = count($this->result);
        }

        return new \ArrayIterator($this->result);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Paginator
     */
    private function createPaginator($offset, $limit)
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
