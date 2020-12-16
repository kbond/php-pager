<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM\Specification\Normalizer;

use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Specification\Field;
use Zenstruck\Porpaginas\Specification\Filter\IsNotNull;
use Zenstruck\Porpaginas\Specification\Filter\IsNull;
use Zenstruck\Porpaginas\Specification\Normalizer;
use Zenstruck\Porpaginas\Specification\Normalizer\ClassMethodMap;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NullNormalizer implements Normalizer
{
    use ClassMethodMap, ORMNormalizer;

    /**
     * @param Field      $specification
     * @param ORMContext $context
     */
    public function normalize($specification, $context): string
    {
        return $context->qb()->expr()->{self::methodFor($specification)}("{$context->alias()}.{$specification->field()}");
    }

    protected static function classMethodMap(): array
    {
        return [
            IsNull::class => 'isNull',
            IsNotNull::class => 'isNotNull',
        ];
    }
}
