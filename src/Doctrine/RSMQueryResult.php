<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Result;

/**
 * @author Florian Klein <florian.klein@free.fr>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryResult implements Result
{
    private $em;
    private $rsm;
    private $qb;
    private $queryBuilderResult;

    /**
     * @param EntityManagerInterface  $em
     * @param ResultSetMappingBuilder $rsm
     * @param QueryBuilder            $qb
     * @param callable|null           $countQueryBuilderModifier
     */
    public function __construct(EntityManagerInterface $em, ResultSetMappingBuilder $rsm, QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->em = $em;
        $this->rsm = $rsm;
        $this->qb = $qb;
        $this->queryBuilderResult = new DBALQueryBuilderResult($qb, $countQueryBuilderModifier);
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        $qb = clone $this->qb;
        $results = function ($offset, $limit) use ($qb) {
            $qb->setFirstResult($offset)
                ->setMaxResults($limit);

            return $this->createQuery($qb)->execute();
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->queryBuilderResult->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getQuery()->execute());
    }

    /**
     * @return NativeQuery
     */
    public function getQuery()
    {
        return $this->createQuery($this->qb);
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return NativeQuery
     */
    private function createQuery(QueryBuilder $qb)
    {
        $query = $this->em->createNativeQuery($qb->getSQL(), $this->rsm);
        $query->setParameters($this->qb->getParameters());

        return $query;
    }
}
