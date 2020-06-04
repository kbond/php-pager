<?php

namespace Zenstruck\Porpaginas;

/**
 * Central abstraction for paginatable results.
 *
 * It allows iterating over the result either paginated using the {@link take}
 * method or non-paginated using the iterator aggregate API.
 */
interface Result extends \Countable, \IteratorAggregate
{
    public function take(int $offset, int $limit): Page;

    /**
     * Return the number of all results in the paginatable.
     */
    public function count(): int;

    /**
     * Return an iterator over all results of the paginatable.
     */
    public function getIterator(): \Traversable;
}
