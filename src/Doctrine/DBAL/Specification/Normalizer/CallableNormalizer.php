<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\DBALContext;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CallableNormalizer implements Normalizer
{
    use DBALNormalizer;

    /**
     * @param callable    $specification
     * @param DBALContext $context
     */
    public function normalize($specification, $context)
    {
        return $specification($context->qb());
    }

    protected function supportsSpecification($specification): bool
    {
        return \is_callable($specification);
    }
}
