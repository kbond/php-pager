<?php

namespace Zenstruck\Porpaginas\Factory;

use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\ResultPaginator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FactoryResult implements Result
{
    use ResultPaginator;

    private $factory;
    private Result $result;

    public function __construct(callable $factory, Result $result)
    {
        $this->factory = $factory;
        $this->result = $result;
    }

    public function take(int $offset, int $limit): Page
    {
        return new FactoryPage($this->factory, $this->result->take($offset, $limit));
    }

    public function count(): int
    {
        return $this->result->count();
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->result as $result) {
            yield \call_user_func($this->factory, $result);
        }
    }
}
