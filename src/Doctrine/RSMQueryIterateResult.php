<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryIterateResult implements Result
{
    private $child;
    private $em;

    /**
     * @param EntityManagerInterface  $em
     * @param ResultSetMappingBuilder $rsm
     * @param QueryBuilder            $qb
     * @param callable|null           $countQueryBuilderModifier
     */
    public function __construct(EntityManagerInterface $em, ResultSetMappingBuilder $rsm, QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->em = $em;
        $this->child = new RSMQueryResult($em, $rsm, $qb, $countQueryBuilderModifier);
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
