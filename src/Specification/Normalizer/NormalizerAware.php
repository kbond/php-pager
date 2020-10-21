<?php

namespace Zenstruck\Porpaginas\Specification\Normalizer;

use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface NormalizerAware
{
    public function setNormalizer(Normalizer $normalizer): void;
}
