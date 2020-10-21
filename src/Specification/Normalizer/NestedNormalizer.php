<?php

namespace Zenstruck\Porpaginas\Specification\Normalizer;

use Zenstruck\Porpaginas\Specification\Nested;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NestedNormalizer implements Normalizer, NormalizerAware
{
    use WithNormalizer;

    /**
     * @param Nested $specification
     * @param mixed  $context
     */
    public function normalize($specification, $context)
    {
        return $this->normalizer()->normalize($specification->child(), $context);
    }

    public function supports($specification, $context): bool
    {
        return $specification instanceof Nested;
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
