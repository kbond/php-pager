<?php

namespace Zenstruck\Porpaginas\Specification;

use Zenstruck\Porpaginas\Specification\Normalizer\NormalizerAware;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class SpecificationNormalizer implements Normalizer
{
    private iterable $normalizers;
    private array $normalizerCache = [];

    /**
     * @param Normalizer[] $normalizers
     */
    public function __construct(iterable $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function normalize($specification, $context)
    {
        if (!$normalizer = $this->getNormalizer($specification, $context)) {
            throw new \RuntimeException(\sprintf('Specification "%s" with context "%s" does not have a supported visitor registered.', get_debug_type($specification), get_debug_type($context)));
        }

        return $normalizer->normalize($specification, $context);
    }

    public function supports($specification, $context): bool
    {
        return null !== $this->getNormalizer($specification, $context);
    }

    public function isCacheable(): bool
    {
        return true;
    }

    private function getNormalizer($specification, $context): ?Normalizer
    {
        $specificationCacheKey = \is_object($specification) ? \get_class($specification) : 'native-'.\gettype($specification);
        $contextCacheKey = \is_object($context) ? \get_class($context) : 'native-'.\gettype($context);

        if (isset($this->normalizerCache[$specificationCacheKey][$contextCacheKey])) {
            return $this->normalizerCache[$specificationCacheKey][$contextCacheKey];
        }

        foreach ($this->normalizers as $normalizer) {
            if (!$normalizer->supports($specification, $context)) {
                continue;
            }

            if ($normalizer instanceof NormalizerAware) {
                $normalizer->setNormalizer($this);
            }

            if ($normalizer->isCacheable()) {
                return $this->normalizerCache[$specificationCacheKey][$contextCacheKey] = $normalizer;
            }

            return $normalizer;
        }

        return null;
    }
}
