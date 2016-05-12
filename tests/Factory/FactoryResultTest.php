<?php

namespace Zenstruck\Porpaginas\Tests\Factory;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Factory\FactoryResult;
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
        $result = new FactoryResult([$this, 'factory'], new ArrayResult(range(0, 30)));
        $results = iterator_to_array($result);
        $this->assertSame('factory 0', $results[0]);

        $results = iterator_to_array($result->take(10, 10));
        $this->assertSame('factory 10', $results[0]);
    }

    protected function createResultWithItems($count)
    {
        return new FactoryResult([$this, 'factory'], new ArrayResult(array_fill(0, $count, 'value')));
    }

    protected function getExpectedFirstValue()
    {
        return 'factory value';
    }

    public function factory($result)
    {
        return 'factory '.$result;
    }
}
