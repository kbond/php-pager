<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CallableNormalizer implements Normalizer
{
    /**
     * @param callable   $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context)
    {
        return $specification($context->qb(), $context->alias());
    }

    public function supports($specification, $context): bool
    {
        return \is_callable($specification) && $context instanceof ORMContext;
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
