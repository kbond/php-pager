<?php

namespace Zenstruck\Porpaginas\Factory;

use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FactoryResult implements Result
{
    private $factory;
    private $result;

    public function __construct(callable $factory, Result $result)
    {
        $this->factory = $factory;
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        return new FactoryPage($this->factory, $this->result->take($offset, $limit));
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->result->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        foreach ($this->result as $result) {
            yield call_user_func($this->factory, $result);
        }
    }
}
