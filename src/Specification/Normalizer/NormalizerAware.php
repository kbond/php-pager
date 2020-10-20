<?php

namespace Zenstruck\Porpaginas\Specification\Normalizer;

use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class NormalizerAware implements Normalizer
{
    private ?Normalizer $normalizer = null;

    public function normalizer(): Normalizer
    {
        if (!$this->normalizer) {
            throw new \RuntimeException('A normalizer has not been set.');
        }

        return $this->normalizer;
    }

    public function setNormalizer(Normalizer $normalizer): self
    {
        $this->normalizer = $normalizer;

        return $this;
    }
}
