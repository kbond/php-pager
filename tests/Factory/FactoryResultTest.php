<?php

namespace Zenstruck\Porpaginas\Tests\Factory;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Factory\FactoryResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\ResultTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FactoryResultTest extends ResultTest
{
    /**
     * @test
     */
    public function it_uses_factory_callback_to_create_result(): void
    {
        $result = new FactoryResult([$this, 'factory'], new ArrayResult(\range(0, 30)));
        $results = \iterator_to_array($result);
        $this->assertSame('factory 0', $results[0]);

        $results = \iterator_to_array($result->take(10, 10));
        $this->assertSame('factory 10', $results[0]);
    }

    public function factory($result): string
    {
        return 'factory '.$result;
    }

    protected function createResultWithItems(int $count): Result
    {
        $array = [];

        for ($i = 1; $i <= $count; ++$i) {
            $array[] = 'value '.$i;
        }

        return new FactoryResult([$this, 'factory'], new ArrayResult($array));
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return 'factory value '.$position;
    }
}
