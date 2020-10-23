<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Filter\Comparison;
use Zenstruck\Porpaginas\Specification\Filter\Equal;
use Zenstruck\Porpaginas\Specification\Filter\GreaterThan;
use Zenstruck\Porpaginas\Specification\Filter\GreaterThanOrEqual;
use Zenstruck\Porpaginas\Specification\Filter\In;
use Zenstruck\Porpaginas\Specification\Filter\LessThan;
use Zenstruck\Porpaginas\Specification\Filter\LessThanOrEqual;
use Zenstruck\Porpaginas\Specification\Filter\Like;
use Zenstruck\Porpaginas\Specification\Filter\NotEqual;
use Zenstruck\Porpaginas\Specification\Filter\NotIn;
use Zenstruck\Porpaginas\Specification\Filter\NotLike;
use Zenstruck\Porpaginas\Specification\Normalizer;
use Zenstruck\Porpaginas\Specification\Normalizer\ClassMethodMap;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ComparisonNormalizer implements Normalizer
{
    use ORMNormalizer, ClassMethodMap;

    /**
     * @param Comparison $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): string
    {
        $parameter = \sprintf('comparison_%d', $context->qb()->getParameters()->count());
        $context->qb()->setParameter($parameter, $specification->value());

        return $context->qb()->expr()->{self::methodFor($specification)}(
            "{$context->alias()}.{$specification->field()}",
            ":{$parameter}"
        );
    }

    protected static function classMethodMap(): array
    {
        return [
            Equal::class => 'eq',
            NotEqual::class => 'neq',
            GreaterThan::class => 'gt',
            GreaterThanOrEqual::class => 'gte',
            LessThan::class => 'lt',
            LessThanOrEqual::class => 'lte',
            Like::class => 'like',
            NotLike::class => 'notLike',
            In::class => 'in',
            NotIn::class => 'notIn',
        ];
    }
}
