<?php

namespace Zenstruck\Porpaginas;

/**
 * Interface for lazy paginators.
 */
interface Page extends \Countable, \IteratorAggregate
{
    public function getCurrentOffset(): int;

    public function getCurrentPage(): int;

    public function getCurrentLimit(): int;

    /**
     * Return the number of results on the currrent page of the {@link Result}.
     */
    public function count(): int;

    /**
     * Return the number of ALL results in the paginatable of {@link Result}.
     */
    public function totalCount(): int;

    /**
     * Return an iterator over selected windows of results of the paginatable.
     */
    public function getIterator(): \Iterator;
}
