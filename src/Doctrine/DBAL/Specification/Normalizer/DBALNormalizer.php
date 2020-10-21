<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\DBALContext;
use Zenstruck\Porpaginas\Specification\Normalizer\SplitSupports;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait DBALNormalizer
{
    use SplitSupports;

    public function isCacheable(): bool
    {
        return true;
    }

    protected function supportsContext($context): bool
    {
        return $context instanceof DBALContext;
    }
}
