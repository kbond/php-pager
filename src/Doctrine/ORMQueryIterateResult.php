<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMQueryIterateResult implements Result
{
    private $em;
    private $child;

    /**
     * @param EntityManagerInterface $em
     * @param Query|QueryBuilder     $query
     * @param bool                   $fetchCollection
     */
    public function __construct(EntityManagerInterface $em, $query, $fetchCollection = true)
    {
        $this->em = $em;
        $this->child = new ORMQueryResult($query, $fetchCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        return $this->child->take($offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->child->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        foreach ($this->child->getQuery()->iterate() as $row) {
            yield $row[0];

            $this->em->detach($row[0]);
        }
    }
}
