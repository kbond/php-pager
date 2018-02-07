<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Pager implements \Countable, \IteratorAggregate, \JsonSerializable
{
    final public function getNextPage(): ?int
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage === $this->getLastPage()) {
            return null;
        }

        return ++$currentPage;
    }

    final public function getPreviousPage(): ?int
    {
        $page = $this->getCurrentPage();

        if (1 === $page) {
            return null;
        }

        return --$page;
    }

    final public function getFirstPage(): int
    {
        return 1;
    }

    final public function getLastPage(): int
    {
        $totalCount = $this->totalCount();

        if (0 === $totalCount) {
            return 1;
        }

        return (int) \ceil($totalCount / $this->getLimit());
    }

    final public function pagesCount(): int
    {
        return $this->getLastPage();
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => \iterator_to_array($this),
            'count' => $this->count(),
            'total' => $this->totalCount(),
            'limit' => $this->getLimit(),
            'pages' => $this->pagesCount(),
            'first' => $this->getFirstPage(),
            'previous' => $this->getPreviousPage(),
            'current' => $this->getCurrentPage(),
            'next' => $this->getNextPage(),
            'last' => $this->getLastPage(),
        ];
    }

    abstract public function getCurrentPage(): int;

    abstract public function getLimit(): int;

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
    abstract public function getIterator(): \Iterator;
}
