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

    public function getCurrentPage(): int
    {
        $lastPage = $this->lastPage();

        if ($this->page > $lastPage) {
            return $lastPage;
        }

        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }

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

    public function nextPage(): ?int
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage === $this->lastPage()) {
            return null;
        }

        return ++$currentPage;
    }

    public function previousPage(): ?int
    {
        $page = $this->getCurrentPage();

        if (1 === $page) {
            return null;
        }

        return --$page;
    }

    public function firstPage(): int
    {
        return 1;
    }

    public function lastPage(): int
    {
        $totalCount = $this->totalCount();

        if (0 === $totalCount) {
            return 1;
        }

        return \ceil($totalCount / $this->limit());
    }

    public function pagesCount(): int
    {
        return $this->lastPage();
    }

    public function haveToPaginate(): bool
    {
        return $this->pagesCount() > 1;
    }

    private function getPage(): Page
    {
        if ($this->cachedPage) {
            return $this->cachedPage;
        }

        $offset = $this->getCurrentPage() * $this->limit() - $this->limit();

        return $this->cachedPage = $this->result->take($offset, $this->limit());
    }
}
