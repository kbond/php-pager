<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMContext
{
    private QueryBuilder $qb;
    private string $alias;

    public function __construct(QueryBuilder $qb, string $alias)
    {
        $this->qb = $qb;
        $this->alias = $alias;
    }

    public function qb(): QueryBuilder
    {
        return $this->qb;
    }

    public function alias(): string
    {
        return $this->alias;
    }
}
