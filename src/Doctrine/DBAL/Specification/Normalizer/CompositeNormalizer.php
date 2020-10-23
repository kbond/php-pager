<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\DBALContext;
use Zenstruck\Porpaginas\Specification\Logic\AndX;
use Zenstruck\Porpaginas\Specification\Logic\Composite;
use Zenstruck\Porpaginas\Specification\Logic\OrX;
use Zenstruck\Porpaginas\Specification\Normalizer;
use Zenstruck\Porpaginas\Specification\Normalizer\ClassMethodMap;
use Zenstruck\Porpaginas\Specification\Normalizer\NormalizerAware;
use Zenstruck\Porpaginas\Specification\Normalizer\WithNormalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CompositeNormalizer implements Normalizer, NormalizerAware
{
    use DBALNormalizer, WithNormalizer, ClassMethodMap;

    /**
     * @param Composite   $specification
     * @param DBALContext $context
     */
    public function normalize($specification, $context): ?CompositeExpression
    {
        $children = \array_filter(\array_map(
            function($child) use ($context) {
                return $this->normalizer()->normalize($child, $context);
            },
            $specification->children()
        ));

        if (empty($children)) {
            return null;
        }

        return $context->qb()->expr()->{self::methodFor($specification)}(...$children);
    }

    protected static function classMethodMap(): array
    {
        return [
            AndX::class => 'andX',
            OrX::class => 'orX',
        ];
    }
}
