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

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        return $this->page->getCurrentPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit()
    {
        return $this->page->getCurrentLimit();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->page->count();
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        return $this->page->totalCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return $this->page;
    }
}
