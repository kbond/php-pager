<?php

namespace Zenstruck\Porpaginas\Callback;

use Zenstruck\Porpaginas\JsonSerializable;
use Zenstruck\Porpaginas\Page;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CallbackPage implements Page
{
    use JsonSerializable;

    private $resultCallback;
    private $totalCountCallback;
    private $offset;
    private $limit;
    private $totalCount;
    private $results;

    /**
     * @param callable $resultCallback     Returns an array
     * @param callable $totalCountCallback Returns an integer
     */
    public function __construct(callable $resultCallback, callable $totalCountCallback, int $offset, int $limit)
    {
        $this->resultCallback = $resultCallback;
        $this->totalCountCallback = $totalCountCallback;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getCurrentOffset(): int
    {
        return $this->offset;
    }

    public function getCurrentPage(): int
    {
        return (int) (\floor($this->offset / $this->limit) + 1);
    }

    public function getCurrentLimit(): int
    {
        return $this->limit;
    }

    public function count(): int
    {
        return \count($this->getResults());
    }

    public function totalCount(): int
    {
        if (null !== $this->totalCount) {
            return $this->totalCount;
        }

        return $this->totalCount = \call_user_func($this->totalCountCallback);
    }

    public function getIterator(): \Iterator
    {
        return $this->getResults();
    }

    private function getResults(): \ArrayIterator
    {
        if (null !== $this->results) {
            return $this->results;
        }

        return $this->results = new \ArrayIterator(\call_user_func($this->resultCallback, $this->offset, $this->limit));
    }
}
