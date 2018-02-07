<?php

namespace Zenstruck\Porpaginas\Arrays;

use Zenstruck\Porpaginas\Result;

final class ArrayResult implements Result
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        return new ArrayPage(
            \array_slice($this->data, $offset, $limit),
            $offset,
            $limit,
            \count($this->data)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
