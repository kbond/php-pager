<?php

namespace Zenstruck\Porpaginas\Pager;

use Zenstruck\Porpaginas\Pager;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ResultPager extends Pager
{
    const DEFAULT_LIMIT = 20;

    private $result;
    private $page;
    private $limit;
    private $cachedPage;

    /**
     * @param Result $result
     * @param int    $page
     * @param int    $limit
     */
    public function __construct(Result $result, $page = 1, $limit = self::DEFAULT_LIMIT)
    {
        if (!is_numeric($page)) {
            $page = 1;
        }

        if (!is_numeric($limit)) {
            $limit = self::DEFAULT_LIMIT;
        }

        $page = (int) $page;
        $limit = (int) $limit;

        if ($page < 1) {
            $page = 1;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $this->result = $result;
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        $lastPage = $this->getLastPage();

        if ($this->page > $lastPage) {
            return $lastPage;
        }

        return $this->page;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getResults()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        return $this->result->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        if ($this->cachedPage !== null) {
            return $this->cachedPage;
        }

        $offset = $this->getCurrentPage() * $this->getLimit() - $this->getLimit();

        return $this->cachedPage = $this->result->take($offset, $this->getLimit());
    }
}
