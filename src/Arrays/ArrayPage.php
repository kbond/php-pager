<?php

namespace Zenstruck\Porpaginas\Arrays;

use Zenstruck\Porpaginas\Page;

final class ArrayPage implements Page
{
    private array $slice;
    private int $offset;
    private int $limit;
    private int $totalCount;

    public function __construct(array $slice, int $offset, int $limit, int $totalCount)
    {
        $this->slice = $slice;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->totalCount = $totalCount;
    }

    public function currentOffset(): int
    {
        return $this->offset;
    }

    public function currentPage(): int
    {
        return \floor($this->offset / $this->limit) + 1;
    }

    public function currentLimit(): int
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

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->slice);
    }

    public function toArray(): array
    {
        return $this->slice;
    }
}
