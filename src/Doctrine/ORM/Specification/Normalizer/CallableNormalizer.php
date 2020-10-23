<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CallableNormalizer implements Normalizer
{
    use ORMNormalizer;

    /**
     * @param callable   $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context)
    {
        return $specification($context->qb(), $context->alias());
    }

    protected function supportsSpecification($specification): bool
    {
        return \is_callable($specification);
    }
}
