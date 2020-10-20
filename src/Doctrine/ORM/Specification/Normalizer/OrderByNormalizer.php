<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Normalizer;
use Zenstruck\Porpaginas\Specification\OrderBy;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class OrderByNormalizer implements Normalizer
{
    /**
     * @param OrderBy    $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): void
    {
        $context->qb()->addOrderBy("{$context->alias()}.{$specification->field()}", $specification->direction());
    }

    public function supports($specification, $context): bool
    {
        return $specification instanceof OrderBy && $context instanceof ORMContext;
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
