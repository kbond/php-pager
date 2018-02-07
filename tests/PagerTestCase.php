<?php

namespace Zenstruck\Porpaginas\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Pager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class PagerTestCase extends TestCase
{
    /**
     * @test
     */
    public function it_properly_handles_page1()
    {
        $pager = $this->createPager(\range(1, 504), 1, 20);

        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->getFirstPage());
        $this->assertSame(2, $pager->getNextPage());
        $this->assertNull($pager->getPreviousPage());
        $this->assertSame(26, $pager->getLastPage());
        $this->assertSame(26, $pager->pagesCount());
        $this->assertSame(504, $pager->totalCount());
        $this->assertSame(20, $pager->getLimit());
        $this->assertCount(20, $pager);
        $this->assertSame(\range(1, 20), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_page2()
    {
        $pager = $this->createPager(\range(1, 504), 2, 20);

        $this->assertSame(2, $pager->getCurrentPage());
        $this->assertSame(1, $pager->getFirstPage());
        $this->assertSame(3, $pager->getNextPage());
        $this->assertSame(1, $pager->getPreviousPage());
        $this->assertCount(20, $pager);
        $this->assertSame(\range(21, 40), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_the_last_page()
    {
        $pager = $this->createPager(\range(1, 504), 26, 20);

        $this->assertSame(26, $pager->getCurrentPage());
        $this->assertSame(1, $pager->getFirstPage());
        $this->assertNull($pager->getNextPage());
        $this->assertSame(25, $pager->getPreviousPage());
        $this->assertCount(4, $pager);
        $this->assertSame(\range(501, 504), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_an_empty_page()
    {
        $pager = $this->createPager([], 1, 20);

        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->getFirstPage());
        $this->assertNull($pager->getNextPage());
        $this->assertNull($pager->getPreviousPage());
        $this->assertSame(1, $pager->getLastPage());
        $this->assertSame(1, $pager->pagesCount());
        $this->assertSame(0, $pager->totalCount());
        $this->assertCount(0, $pager);
        $this->assertSame([], \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_properly_handles_a_single_page()
    {
        $pager = $this->createPager(\range(1, 10), 1, 20);

        $this->assertSame(1, $pager->getCurrentPage());
        $this->assertSame(1, $pager->getFirstPage());
        $this->assertNull($pager->getNextPage());
        $this->assertNull($pager->getPreviousPage());
        $this->assertSame(1, $pager->getLastPage());
        $this->assertSame(1, $pager->pagesCount());
        $this->assertSame(10, $pager->totalCount());
        $this->assertCount(10, $pager);
        $this->assertSame(\range(1, 10), \iterator_to_array($pager));
    }

    /**
     * @test
     */
    public function it_is_json_serializable()
    {
        $pager = $this->createPager(\range(1, 10), 2, 3);
        $expected = \json_encode([
            'items' => \range(4, 6),
            'count' => 3,
            'total' => 10,
            'limit' => 3,
            'pages' => 4,
            'first' => 1,
            'previous' => 1,
            'current' => 2,
            'next' => 3,
            'last' => 4,
        ]);

        $this->assertSame($expected, \json_encode($pager));
    }

    abstract protected function createPager(array $results, int $page, int $limit): Pager;
}
