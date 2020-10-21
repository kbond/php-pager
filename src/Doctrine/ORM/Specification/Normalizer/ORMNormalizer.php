<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Normalizer\SplitSupports;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait ORMNormalizer
{
    use SplitSupports;

    public function isCacheable(): bool
    {
        return true;
    }

    protected function supportsContext($context): bool
    {
        return $context instanceof ORMContext;
    }
}
