<?php

namespace Zenstruck\Porpaginas\Arrays;

use Zenstruck\Porpaginas\Page;

final class ArrayPage implements Page
{
    private $slice;
    private $offset;
    private $limit;
    private $totalCount;

    public function __construct(array $slice, int $offset, int $limit, int $totalCount)
    {
        $this->slice = $slice;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->totalCount = $totalCount;
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
        return \count($this->slice);
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->slice);
    }
}
