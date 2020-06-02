<?php

namespace Zenstruck\Porpaginas\Bridge\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use Zenstruck\Porpaginas\Result;

final class PorpaginasAdapter implements AdapterInterface
{
    private Result $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function getNbResults(): int
    {
        return $this->result->count();
    }

    public function getSlice($offset, $length): iterable
    {
        return $this->result->take($offset, $length);
    }
}
