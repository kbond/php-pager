<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Filter\IsNull;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class IsNullNormalizer implements Normalizer
{
    /**
     * @param IsNull     $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): string
    {
        return $context->qb()->expr()->isNull("{$context->alias()}.{$specification->field()}");
    }

    public function supports($specification, $context): bool
    {
        return $specification instanceof IsNull && $context instanceof ORMContext;
    }

    public function isCacheable(): bool
    {
        return true;
    }
}
