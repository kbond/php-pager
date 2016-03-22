<?php

namespace Zenstruck\Porpaginas\Tests\Arrays;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

class ArrayResultTest extends ResultTestCase
{
    protected function createResultWithItems($count)
    {
        return new ArrayResult(array_fill(0, $count, 'value'));
    }
}
