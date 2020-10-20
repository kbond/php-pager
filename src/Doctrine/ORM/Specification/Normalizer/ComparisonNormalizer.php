<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Doctrine\ORM\Query\Expr;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Filter\Comparison;
use Zenstruck\Porpaginas\Specification\Filter\EqualTo;
use Zenstruck\Porpaginas\Specification\Filter\GreaterThan;
use Zenstruck\Porpaginas\Specification\Filter\GreaterThanOrEqual;
use Zenstruck\Porpaginas\Specification\Filter\In;
use Zenstruck\Porpaginas\Specification\Filter\LessThan;
use Zenstruck\Porpaginas\Specification\Filter\LessThanOrEqual;
use Zenstruck\Porpaginas\Specification\Filter\Like;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ComparisonNormalizer implements Normalizer
{
    private const CLASS_MAP = [
        EqualTo::class => 'eq',
        GreaterThan::class => 'gt',
        GreaterThanOrEqual::class => 'gte',
        LessThan::class => 'lt',
        LessThanOrEqual::class => 'lte',
        Like::class => 'like',
        In::class => 'in',
    ];

    /**
     * @param Comparison $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): Expr\Comparison
    {
        $parameter = \sprintf('comparison_%d', $context->qb()->getParameters()->count());
        $context->qb()->setParameter($parameter, $specification->value());

        return $context->qb()->expr()->{self::CLASS_MAP[\get_class($specification)]}(
            "{$context->alias()}.{$specification->field()}",
            ":{$parameter}"
        );
    }

    public function supports($specification, $context): bool
    {
        if (!\is_object($specification) || !$context instanceof ORMContext) {
            return false;
        }

        return isset(self::CLASS_MAP[\get_class($specification)]);
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
