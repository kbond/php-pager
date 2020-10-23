<?php

namespace Zenstruck\Porpaginas;

use Zenstruck\Porpaginas\Exception\NotFound;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Matchable
{
    public function match($specification): Result;

    /**
     * @throws NotFound if no match was found
     */
    public function matchOne($specification);
}
