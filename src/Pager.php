<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Pager implements \Countable
{
    /**
     * @return int|null
     */
    final public function getNextPage()
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage === $this->getLastPage()) {
            return null;
        }

        return ++$currentPage;
    }

    /**
     * @return int|null
     */
    final public function getPreviousPage()
    {
        $page = $this->getCurrentPage();

        if (1 === $page) {
            return null;
        }

        return --$page;
    }

    /**
     * @return int
     */
    final public function getFirstPage()
    {
        return 1;
    }

    /**
     * @return int
     */
    final public function getLastPage()
    {
        $totalCount = $this->totalCount();

        if (0 === $totalCount) {
            return 1;
        }

        return (int) \ceil($totalCount / $this->getLimit());
    }

    /**
     * @return int
     */
    final public function pagesCount()
    {
        return $this->getLastPage();
    }

    /**
     * @return int
     */
    abstract public function getCurrentPage();

    /**
     * @return int
     */
    abstract public function getLimit();

    /**
     * The result count for the current page.
     *
     * @return int
     */
    abstract public function count();

    /**
     * The total result count.
     *
     * @return int
     */
    abstract public function totalCount();

    /**
     * @return Page
     */
    abstract public function getResults();
}
