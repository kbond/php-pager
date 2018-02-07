<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryIterateResult implements Result
{
    private $child;
    private $em;

    public function __construct(EntityManagerInterface $em, ResultSetMappingBuilder $rsm, QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->em = $em;
        $this->child = new RSMQueryResult($em, $rsm, $qb, $countQueryBuilderModifier);
    }

    public function take(int $offset, int $limit): Page
    {
        return $this->child->take($offset, $limit);
    }

    public function count(): int
    {
        return $this->child->count();
    }

    public function getIterator(): \Iterator
    {
        foreach ($this->child->getQuery()->iterate() as $row) {
            yield $row[0];

            $this->em->detach($row[0]);
        }
    }
}
