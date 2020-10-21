<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\DBALContext;
use Zenstruck\Porpaginas\Specification\Normalizer;
use Zenstruck\Porpaginas\Specification\OrderBy;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class OrderByNormalizer implements Normalizer
{
    use DBALNormalizer;

    /**
     * @param OrderBy     $specification
     * @param DBALContext $context
     */
    public function normalize($specification, $context): void
    {
        $context->qb()->addOrderBy($specification->field(), $specification->direction());
    }

    protected function supportsSpecification($specification): bool
    {
        return $specification instanceof OrderBy;
    }
}
