<?php

namespace Zenstruck\Porpaginas\Specification\Logic;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Not
{
    private object $child;

    public function __construct(object $child)
    {
        $this->child = $child;
    }

    public function child(): object
    {
        return $this->child;
    }
}
