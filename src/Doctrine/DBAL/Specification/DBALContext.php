<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL\Specification;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer\CallableNormalizer;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer\ComparisonNormalizer;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer\CompositeNormalizer;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer\NullNormalizer;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer\OrderByNormalizer;
use Zenstruck\Porpaginas\Specification\Normalizer\NestedNormalizer;
use Zenstruck\Porpaginas\Specification\SpecificationNormalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALContext
{
    private static ?SpecificationNormalizer $defaultNormalizer = null;

    private QueryBuilder $qb;

    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public static function defaultNormalizer(): SpecificationNormalizer
    {
        return self::$defaultNormalizer ?: self::$defaultNormalizer = new SpecificationNormalizer([
            new NestedNormalizer(),
            new CallableNormalizer(),
            new ComparisonNormalizer(),
            new CompositeNormalizer(),
            new NullNormalizer(),
            new OrderByNormalizer(),
        ]);
    }

    public function qb(): QueryBuilder
    {
        return $this->qb;
    }
}
