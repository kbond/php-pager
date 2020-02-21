<?php

namespace Zenstruck\Porpaginas\Pager;

use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Pager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PagePager extends Pager
{
    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function getCurrentPage(): int
    {
        return $this->page->getCurrentPage();
    }

    public function getLimit(): int
    {
        return $this->page->getCurrentLimit();
    }

    public function count(): int
    {
        return $this->page->count();
    }

    public function totalCount(): int
    {
        return $this->page->totalCount();
    }

    public function getIterator(): iterable
    {
        return $this->page->getIterator();
    }
}
