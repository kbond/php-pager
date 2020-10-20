<?php

namespace Zenstruck\Porpaginas\Specification;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Normalizer
{
    /**
     * @param mixed $specification
     * @param mixed $context
     *
     * @return mixed
     */
    public function normalize($specification, $context);

    /**
     * @param mixed $specification
     * @param mixed $context
     */
    public function supports($specification, $context): bool;

    /**
     * Whether or not the result of supports() can be cached by specification and context.
     */
    public function isCacheable(): bool;
}
