<?php

namespace Zenstruck\Porpaginas;

/**
 * Interface for lazy paginators.
 */
interface Page extends \Countable, \IteratorAggregate
{
    /**
     * @return int
     */
    public function getCurrentOffset();

    /**
     * @return int
     */
    public function getCurrentPage();

    /**
     * @return int
     */
    public function getCurrentLimit();

    /**
     * Return the number of results on the currrent page of the {@link Result}.
     *
     * @return int
     */
    public function count();

    /**
     * Return the number of ALL results in the paginatable of {@link Result}.
     *
     * @return int
     */
    public function totalCount();

    /**
     * Return an iterator over selected windows of results of the paginatable.
     *
     * @return \Iterator
     */
    public function getIterator();
}
