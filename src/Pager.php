<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Pager implements \Countable, \IteratorAggregate
{
    final public function nextPage(): ?int
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage === $this->lastPage()) {
            return null;
        }

        return ++$currentPage;
    }

    final public function previousPage(): ?int
    {
        $page = $this->getCurrentPage();

        if (1 === $page) {
            return null;
        }

        return --$page;
    }

    final public function firstPage(): int
    {
        return 1;
    }

    final public function lastPage(): int
    {
        $totalCount = $this->totalCount();

        if (0 === $totalCount) {
            return 1;
        }

        return \ceil($totalCount / $this->limit());
    }

    final public function pagesCount(): int
    {
        return $this->lastPage();
    }

    final public function haveToPaginate(): bool
    {
        return $this->pagesCount() > 1;
    }

    final public function toArray(): array
    {
        return \iterator_to_array($this);
    }

    abstract public function getCurrentPage(): int;

    abstract public function limit(): int;

    /**
     * The result count for the current page.
     */
    abstract public function count(): int;

    /**
     * The total result count.
     */
    abstract public function totalCount(): int;

    /**
     * Return an iterator over selected windows of results of the paginatable.
     */
    abstract public function getIterator(): \Traversable;
}
