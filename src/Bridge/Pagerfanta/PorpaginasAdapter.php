<?php

namespace Zenstruck\Porpaginas\Bridge\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use Zenstruck\Porpaginas\Result;

class PorpaginasAdapter implements AdapterInterface
{
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->result->take(0, 1)->totalCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        return iterator_to_array($this->result->take($offset, $length));
    }
}
