<?php

namespace Zenstruck\Porpaginas\Bridge\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use Zenstruck\Porpaginas\Result;

final class PorpaginasAdapter implements AdapterInterface
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
        return $this->result->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        return iterator_to_array($this->result->take($offset, $length));
    }
}
