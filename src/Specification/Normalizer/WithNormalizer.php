<?php

namespace Zenstruck\Porpaginas\Specification\Normalizer;

use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait WithNormalizer
{
    private ?Normalizer $normalizer = null;

    public function setNormalizer(Normalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    protected function normalizer(): Normalizer
    {
        if (!$this->normalizer) {
            throw new \RuntimeException('A normalizer has not been set.');
        }

        return $this->normalizer;
    }
}
