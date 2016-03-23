<?php

namespace Zenstruck\Porpaginas\Callback;

use Zenstruck\Porpaginas\Page;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CallbackPage implements Page
{
    private $resultCallback;
    private $totalCountCallback;
    private $offset;
    private $limit;
    private $totalCount;
    private $results;

    /**
     * @param callable $resultCallback     Returns an iterator
     * @param callable $totalCountCallback Returns an integer
     * @param int      $offset
     * @param int      $limit
     */
    public function __construct(callable $resultCallback, callable $totalCountCallback, $offset, $limit)
    {
        $this->resultCallback = $resultCallback;
        $this->totalCountCallback = $totalCountCallback;
        $this->offset = $offset;
        $this->limit = $limit;
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
        return (int) (floor($this->offset / $this->limit) + 1);
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
        return count($this->getResults());
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        if ($this->totalCount !== null) {
            return $this->totalCount;
        }

        return $this->totalCount = call_user_func($this->totalCountCallback);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->getResults();
    }

    /**
     * @return \ArrayIterator
     */
    private function getResults()
    {
        if ($this->results !== null) {
            return $this->results;
        }

        return $this->results = new \ArrayIterator(call_user_func($this->resultCallback, $this->offset, $this->limit));
    }
}
