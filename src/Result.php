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
    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Page
     */
    public function take($offset, $limit);

    /**
     * Return the number of all results in the paginatable.
     
     * @return int
     */
    public function count();

    /**
     * Return an iterator over all results of the paginatable.
     *
     * @return \Iterator
     */
    public function getIterator();
}
