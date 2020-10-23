<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Doctrine\ORM\Query\Expr;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
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
    use ORMNormalizer, WithNormalizer, ClassMethodMap;

    /**
     * @param Composite  $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): ?Expr\Composite
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
