<?php

namespace Zenstruck\Porpaginas;

use Zenstruck\Porpaginas\Exception\NotFound;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Repository extends \IteratorAggregate, \Countable
{
    /**
     * Fetch a single item that matches the given specification.
     *
     * @return mixed
     *
     * @throws NotFound if no matching item was found
     */
    public function get(callable $specification);

    /**
     * Fetches items that match the given specification.
     */
    public function filter(callable $specification): Result;
}
