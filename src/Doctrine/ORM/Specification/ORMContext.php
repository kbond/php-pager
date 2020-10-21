<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification;

use Doctrine\ORM\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\CallableNormalizer;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\ComparisonNormalizer;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\CompositeNormalizer;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\IsNullNormalizer;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\NotNormalizer;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer\OrderByNormalizer;
use Zenstruck\Porpaginas\Specification\Normalizer\NestedNormalizer;
use Zenstruck\Porpaginas\Specification\SpecificationNormalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMContext
{
    private static ?SpecificationNormalizer $defaultNormalizer = null;

    private QueryBuilder $qb;
    private string $alias;

    public function __construct(QueryBuilder $qb, string $alias)
    {
        $this->qb = $qb;
        $this->alias = $alias;
    }

    public static function defaultNormalizer(): SpecificationNormalizer
    {
        return self::$defaultNormalizer ?: self::$defaultNormalizer = new SpecificationNormalizer([
            new NestedNormalizer(),
            new CallableNormalizer(),
            new ComparisonNormalizer(),
            new CompositeNormalizer(),
            new IsNullNormalizer(),
            new NotNormalizer(),
            new OrderByNormalizer(),
        ]);
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
