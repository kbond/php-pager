<?php

namespace Zenstruck\Porpaginas\Arrays;

use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

final class ArrayResult implements Result
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function take(int $offset, int $limit): Page
    {
        return new ArrayPage(
            \array_slice($this->data, $offset, $limit),
            $offset,
            $limit,
            \count($this->data)
        );
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->data);
    }
}
