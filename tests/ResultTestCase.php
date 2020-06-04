<?php

namespace Zenstruck\Porpaginas\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Result;

abstract class ResultTestCase extends TestCase
{
    /**
     * @test
     */
    public function it_counts_total_items(): void
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function it_iterates_over_all_items(): void
    {
        $result = $this->createResultWithItems(11);

        $this->assertCount(11, \iterator_to_array($result));
    }

    /**
     * @test
     */
    public function it_takes_slice_as_page(): void
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
    public function it_counts_last_page_of_slice_correctly(): void
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
    public function it_counts_page_first_then_iterates(): void
    {
        $result = $this->createResultWithItems(16);

        $page = $result->take(10, 5);

        $this->assertCount(5, $page);
        $this->assertCount(5, \iterator_to_array($page));
    }

    /**
     * @test
     */
    public function it_itereates_first_then_counts_page(): void
    {
        $result = $this->createResultWithItems(16);

        $page = $result->take(10, 5);

        $this->assertCount(5, \iterator_to_array($page));
        $this->assertCount(5, $page);
    }

    /**
     * @test
     */
    public function results_match_the_expected_value(): void
    {
        $result = $this->createResultWithItems(11);

        $this->assertEquals($this->getExpectedValueAtPosition(1), \iterator_to_array($result)[0]);
        $this->assertEquals($this->getExpectedValueAtPosition(5), \iterator_to_array($result)[4]);
        $this->assertEquals($this->getExpectedValueAtPosition(11), \iterator_to_array($result)[10]);

        $page = $result->take(0, 10);

        $this->assertEquals($this->getExpectedValueAtPosition(1), \iterator_to_array($page)[0]);
        $this->assertEquals($this->getExpectedValueAtPosition(10), \iterator_to_array($page)[9]);

        $page = $result->take(10, 10);

        $this->assertEquals($this->getExpectedValueAtPosition(11), \iterator_to_array($page)[0]);
    }

    /**
     * @test
     */
    public function it_can_have_empty_results(): void
    {
        $result = $this->createResultWithItems(0);

        $this->assertCount(0, $result);
        $this->assertSame([], \iterator_to_array($result));
        $this->assertSame([], \iterator_to_array($result->take(0, 10)));
    }

    abstract protected function createResultWithItems(int $count): Result;

    /**
     * @return mixed
     */
    abstract protected function getExpectedValueAtPosition(int $position);
}
