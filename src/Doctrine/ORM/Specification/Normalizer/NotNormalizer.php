<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Doctrine\ORM\Query\Expr;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Logic\Not;
use Zenstruck\Porpaginas\Specification\Normalizer\NormalizerAware;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NotNormalizer extends NormalizerAware
{
    /**
     * @param Not        $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): Expr\Func
    {
        return $context->qb()->expr()->not($this->normalizer()->normalize($specification->child(), $context));
    }

    public function supports($specification, $context): bool
    {
        return $specification instanceof Not && $context instanceof ORMContext;
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
