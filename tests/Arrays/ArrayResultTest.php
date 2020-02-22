<?php

namespace Zenstruck\Porpaginas\Tests\Arrays;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

class ArrayResultTest extends ResultTestCase
{
    protected function createResultWithItems(int $count): Result
    {
        $array = [];

        for ($i = 1; $i <= $count; ++$i) {
            $array[] = 'value '.$i;
        }

        return new ArrayResult($array);
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return 'value '.$position;
    }
}
