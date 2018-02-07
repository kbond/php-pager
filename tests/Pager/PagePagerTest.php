<?php

namespace Zenstruck\Porpaginas\Tests\Pager;

use Zenstruck\Porpaginas\Arrays\ArrayPage;
use Zenstruck\Porpaginas\Pager\PagePager;
use Zenstruck\Porpaginas\Tests\PagerTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PagePagerTest extends PagerTestCase
{
    protected function createPager(array $results, $page, $limit)
    {
        $offset = (int) (($page - 1) * $limit);
        $slice = \array_values(\array_slice($results, $offset, $limit));

        return new PagePager(new ArrayPage($slice, $offset, $limit, \count($results)));
    }
}
