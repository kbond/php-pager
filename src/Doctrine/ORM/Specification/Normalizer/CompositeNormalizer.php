<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Doctrine\ORM\Query\Expr;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Logic\AndX;
use Zenstruck\Porpaginas\Specification\Logic\Composite;
use Zenstruck\Porpaginas\Specification\Logic\OrX;
use Zenstruck\Porpaginas\Specification\Normalizer\NormalizerAware;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CompositeNormalizer extends NormalizerAware
{
    private const CLASS_MAP = [
        AndX::class => 'andX',
        OrX::class => 'orX',
    ];

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

        return $context->qb()->expr()->{self::CLASS_MAP[\get_class($specification)]}(...$children);
    }

    public function supports($specification, $context): bool
    {
        if (!\is_object($specification) || !$context instanceof ORMContext) {
            return false;
        }

        return \array_key_exists(\get_class($specification), self::CLASS_MAP);
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
