<?php

namespace Zenstruck\Porpaginas\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Pager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_properly_handles_page1(): void
    {
        $pager = $this->createPager(\range(1, 504), 1, 20);

        $this->assertTrue($pager->haveToPaginate());
        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertSame(2, $pager->nextPage());
        $this->assertNull($pager->previousPage());
        $this->assertSame(26, $pager->lastPage());
        $this->assertSame(26, $pager->pagesCount());
        $this->assertSame(504, $pager->totalCount());
        $this->assertSame(20, $pager->limit());
        $this->assertCount(20, $pager);
        $this->assertSame(\range(1, 20), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_page2(): void
    {
        $pager = $this->createPager(\range(1, 504), 2, 20);

        $this->assertTrue($pager->haveToPaginate());
        $this->assertSame(2, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertSame(3, $pager->nextPage());
        $this->assertSame(1, $pager->previousPage());
        $this->assertCount(20, $pager);
        $this->assertSame(\range(21, 40), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_the_last_page(): void
    {
        $pager = $this->createPager(\range(1, 504), 26, 20);

        $this->assertTrue($pager->haveToPaginate());
        $this->assertSame(26, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertNull($pager->nextPage());
        $this->assertSame(25, $pager->previousPage());
        $this->assertCount(4, $pager);
        $this->assertSame(\range(501, 504), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_an_empty_page(): void
    {
        $pager = $this->createPager([], 1, 20);

        $this->assertFalse($pager->haveToPaginate());
        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertNull($pager->nextPage());
        $this->assertNull($pager->previousPage());
        $this->assertSame(1, $pager->lastPage());
        $this->assertSame(1, $pager->pagesCount());
        $this->assertSame(0, $pager->totalCount());
        $this->assertCount(0, $pager);
        $this->assertSame([], \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_a_single_page(): void
    {
        $pager = $this->createPager(\range(1, 10), 1, 20);

        $this->assertFalse($pager->haveToPaginate());
        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertNull($pager->nextPage());
        $this->assertNull($pager->previousPage());
        $this->assertSame(1, $pager->lastPage());
        $this->assertSame(1, $pager->pagesCount());
        $this->assertSame(10, $pager->totalCount());
        $this->assertCount(10, $pager);
        $this->assertSame(\range(1, 10), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_a_too_large_page_as_the_last_page(): void
    {
        $pager = $this->createPager(\range(1, 504), 30, 20);

        $this->assertSame(26, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertNull($pager->nextPage());
        $this->assertSame(25, $pager->previousPage());
        $this->assertCount(4, $pager);
        $this->assertSame(\range(501, 504), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function invalid_page(): void
    {
        $pager = $this->createPager([], 0);
        $this->assertSame(1, $pager->getCurrentPage());

        $pager = $this->createPager([], -1);
        $this->assertSame(1, $pager->getCurrentPage());
    }

    /**
     * @test
     */
    public function invalid_limit(): void
    {
        $pager = $this->createPager([], 1, 0);
        $this->assertSame(20, $pager->limit());

        $pager = $this->createPager([], 1, -1);
        $this->assertSame(20, $pager->limit());
    }

    protected function createPager(array $results, int $page, int $limit = Pager::DEFAULT_LIMIT): Pager
    {
        return new Pager(new ArrayResult($results), $page, $limit);
    }
}
