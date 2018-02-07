<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\JsonSerializableIterator;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

/**
 * @author Florian Klein <florian.klein@free.fr>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryResult implements Result
{
    use JsonSerializableIterator;

    private $em;
    private $rsm;
    private $qb;
    private $queryBuilderResult;

    public function __construct(EntityManagerInterface $em, ResultSetMappingBuilder $rsm, QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->em = $em;
        $this->rsm = $rsm;
        $this->qb = $qb;
        $this->queryBuilderResult = new DBALQueryBuilderResult($qb, $countQueryBuilderModifier);
    }

    public function take(int $offset, int $limit): Page
    {
        $qb = clone $this->qb;
        $results = function ($offset, $limit) use ($qb) {
            $qb->setFirstResult($offset)
                ->setMaxResults($limit)
            ;

            return $this->createQuery($qb)->execute();
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    public function count(): int
    {
        return $this->queryBuilderResult->count();
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->getQuery()->execute());
    }

    public function getQuery(): NativeQuery
    {
        return $this->createQuery($this->qb);
    }

    private function createQuery(QueryBuilder $qb): NativeQuery
    {
        $query = $this->em->createNativeQuery($qb->getSQL(), $this->rsm);
        $query->setParameters($this->qb->getParameters());

        return $query;
    }
}
