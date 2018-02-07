<?php

namespace Zenstruck\Porpaginas\Tests\Pager;

use Zenstruck\Porpaginas\Arrays\ArrayPage;
use Zenstruck\Porpaginas\Pager\FactoryPager;
use Zenstruck\Porpaginas\Tests\PagerTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PageFactoryPagerTest extends PagerTestCase
{
    protected function createPager(array $results, $page, $limit)
    {
        $offset = (int) (($page - 1) * $limit);
        $slice = \array_values(\array_slice($results, $offset, $limit));

        return FactoryPager::fromPage(
            function ($value) {
                return $value;
            },
            new ArrayPage($slice, $offset, $limit, \count($results))
        );
    }
}
