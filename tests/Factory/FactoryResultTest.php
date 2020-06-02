<?php

namespace Zenstruck\Porpaginas\Tests\Factory;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Factory\FactoryResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FactoryResultTest extends ResultTestCase
{
    /**
     * @test
     */
    public function it_uses_factory_callback_to_create_result()
    {
        $result = new FactoryResult([$this, 'factory'], new ArrayResult(\range(0, 30)));
        $this->assertSame('factory 0', $result->toArray()[0]);

        $results = $result->take(10, 10)->toArray();
        $this->assertSame('factory 10', $results[0]);
    }

    public function factory($result)
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
