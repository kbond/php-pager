<?php

namespace Zenstruck\Porpaginas\Tests\Pager;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Pager;
use Zenstruck\Porpaginas\Pager\ResultPager;
use Zenstruck\Porpaginas\Tests\PagerTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ResultPagerTest extends PagerTestCase
{
    /**
     * @test
     */
    public function it_properly_handles_a_too_large_page_as_the_last_page()
    {
        $pager = $this->createPager(\range(1, 504), 30, 20);

        $this->assertSame(26, $pager->getCurrentPage());
        $this->assertSame(1, $pager->firstPage());
        $this->assertNull($pager->nextPage());
        $this->assertSame(25, $pager->previousPage());
        $this->assertCount(4, $pager);
        $this->assertSame(\range(501, 504), $pager->toArray());
    }

    /**
     * @test
     */
    public function invalid_page()
    {
        $pager = new ResultPager(new ArrayResult([]), 0);
        $this->assertSame(1, $pager->getCurrentPage());

        $pager = new ResultPager(new ArrayResult([]), -1);
        $this->assertSame(1, $pager->getCurrentPage());
    }

    /**
     * @test
     */
    public function invalid_limit()
    {
        $pager = new ResultPager(new ArrayResult([]), 1, 0);
        $this->assertSame(20, $pager->limit());

        $pager = new ResultPager(new ArrayResult([]), 1, -1);
        $this->assertSame(20, $pager->limit());
    }

    protected function createPager(array $results, int $page, int $limit): Pager
    {
        return new ResultPager(new ArrayResult($results), $page, $limit);
    }
}
