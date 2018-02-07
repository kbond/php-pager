<?php

namespace Zenstruck\Porpaginas\Tests\Pager;

use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Pager\FactoryPager;
use Zenstruck\Porpaginas\Tests\PagerTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ResultFactoryPagerTest extends PagerTestCase
{
    protected function createPager(array $results, $page, $limit)
    {
        return FactoryPager::fromResult(
            function ($value) {
                return $value;
            },
            new ArrayResult($results),
            $page,
            $limit
        );
    }
}
