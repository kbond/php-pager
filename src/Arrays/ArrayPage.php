<?php

namespace Zenstruck\Porpaginas\Arrays;

use Zenstruck\Porpaginas\Page;

final class ArrayPage implements Page
{
    private $slice;
    private $offset;
    private $limit;
    private $totalCount;

    /**
     * @param array $slice
     * @param int   $offset
     * @param int   $limit
     * @param int   $totalCount
     */
    public function __construct(array $slice, $offset, $limit, $totalCount)
    {
        $this->slice = $slice;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->totalCount = $totalCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentOffset()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        return floor($this->offset / $this->limit) + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentLimit()
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->slice);
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->slice);
    }
}
