<?php

namespace Zenstruck\Porpaginas\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Result;

abstract class ResultTestCase extends TestCase
{
    /**
     * @test
     */
    public function it_counts_total_items()
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function it_iterates_over_all_items()
    {
        $result = $this->createResultWithItems(11);

        $this->assertCount(11, $result->toArray());
    }

    /**
     * @test
     */
    public function it_takes_slice_as_page()
    {
        $result = $this->createResultWithItems(11);

        $page = $result->take(0, 10);

        $this->assertEquals(1, $page->currentPage());
        $this->assertEquals(0, $page->currentOffset());
        $this->assertEquals(10, $page->currentLimit());
        $this->assertCount(10, $page);
        $this->assertEquals(11, $page->totalCount());
    }

    /**
     * @test
     */
    public function it_counts_last_page_of_slice_correctly()
    {
        $result = $this->createResultWithItems(11);

        $page = $result->take(10, 10);

        $this->assertEquals(2, $page->currentPage());
        $this->assertEquals(10, $page->currentOffset());
        $this->assertEquals(10, $page->currentLimit());
        $this->assertCount(1, $page);
    }

    /**
     * @test
     */
    public function it_counts_page_first_then_iterates()
    {
        $result = $this->createResultWithItems(16);

        $page = $result->take(10, 5);

        $this->assertCount(5, $page);
        $this->assertCount(5, $page->toArray());
    }

    /**
     * @test
     */
    public function it_itereates_first_then_counts_page()
    {
        $result = $this->createResultWithItems(16);

        $page = $result->take(10, 5);

        $this->assertCount(5, $page->toArray());
        $this->assertCount(5, $page);
    }

    /**
     * @test
     */
    public function results_match_the_expected_value()
    {
        $result = $this->createResultWithItems(11);

        $this->assertEquals($this->getExpectedValueAtPosition(1), $result->toArray()[0]);
        $this->assertEquals($this->getExpectedValueAtPosition(5), $result->toArray()[4]);
        $this->assertEquals($this->getExpectedValueAtPosition(11), $result->toArray()[10]);

        $page = $result->take(0, 10);

        $this->assertEquals($this->getExpectedValueAtPosition(1), $page->toArray()[0]);
        $this->assertEquals($this->getExpectedValueAtPosition(10), $page->toArray()[9]);

        $page = $result->take(10, 10);

        $this->assertEquals($this->getExpectedValueAtPosition(11), $page->toArray()[0]);
    }

    /**
     * @test
     */
    public function it_can_have_empty_results()
    {
        $result = $this->createResultWithItems(0);

        $this->assertCount(0, $result);
        $this->assertSame([], $result->toArray());
        $this->assertSame([], $result->take(0, 10)->toArray());
    }

    abstract protected function createResultWithItems(int $count): Result;

    /**
     * @return mixed
     */
    abstract protected function getExpectedValueAtPosition(int $position);
}
