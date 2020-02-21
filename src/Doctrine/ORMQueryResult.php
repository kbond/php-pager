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
