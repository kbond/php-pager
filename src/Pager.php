<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Pager implements \Countable, \IteratorAggregate
{
    public const DEFAULT_LIMIT = 20;

    private Result $result;
    private int $page;
    private int $limit;
    private ?Page $cachedPage = null;

    public function __construct(Result $result, int $page = 1, int $limit = self::DEFAULT_LIMIT)
    {
        $this->result = $result;
        $this->page = $page < 1 ? 1 : $page;
        $this->limit = $limit < 1 ? self::DEFAULT_LIMIT : $limit;
    }

    public function current(): int
    {
        $lastPage = $this->last();

        if ($this->page > $lastPage) {
            return $lastPage;
        }

        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int the count for the current page
     */
    public function count(): int
    {
        return $this->getPage()->count();
    }

    public function totalCount(): int
    {
        return $this->result->count();
    }

    public function getIterator(): \Traversable
    {
        return $this->getPage()->getIterator();
    }

    public function next(): ?int
    {
        $currentPage = $this->current();

        if ($currentPage === $this->last()) {
            return null;
        }

        return ++$currentPage;
    }

    public function previous(): ?int
    {
        $page = $this->current();

        if (1 === $page) {
            return null;
        }

        return --$page;
    }

    public function first(): int
    {
        return 1;
    }

    public function last(): int
    {
        $totalCount = $this->totalCount();

        if (0 === $totalCount) {
            return 1;
        }

        return \ceil($totalCount / $this->limit());
    }

    public function pageCount(): int
    {
        return $this->last();
    }

    public function haveToPaginate(): bool
    {
        return $this->pageCount() > 1;
    }

    private function getPage(): Page
    {
        if ($this->cachedPage) {
            return $this->cachedPage;
        }

        $offset = $this->current() * $this->limit() - $this->limit();

        return $this->cachedPage = $this->result->take($offset, $this->limit());
    }
}
