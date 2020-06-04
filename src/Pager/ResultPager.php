<?php

namespace Zenstruck\Porpaginas\Pager;

use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Pager;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ResultPager extends Pager
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

    private function getPage(): Page
    {
        if ($this->cachedPage) {
            return $this->cachedPage;
        }

        $offset = $this->getCurrentPage() * $this->limit() - $this->limit();

        return $this->cachedPage = $this->result->take($offset, $this->limit());
    }
}
