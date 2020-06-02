<?php

namespace Zenstruck\Porpaginas\Pager;

use Zenstruck\Porpaginas\Factory\FactoryPage;
use Zenstruck\Porpaginas\Factory\FactoryResult;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Pager;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FactoryPager extends Pager
{
    private $inner;

    private function __construct(Pager $inner)
    {
        $this->inner = $inner;
    }

    public static function fromResult(callable $factory, Result $result, int $page = 1, int $limit = ResultPager::DEFAULT_LIMIT): self
    {
        return new self(new ResultPager(new FactoryResult($factory, $result), $page, $limit));
    }

    public static function fromPage(callable $factory, Page $page): self
    {
        return new self(new PagePager(new FactoryPage($factory, $page)));
    }

    public function getCurrentPage(): int
    {
        return $this->inner->getCurrentPage();
    }

    public function limit(): int
    {
        return $this->inner->limit();
    }

    public function count(): int
    {
        return $this->inner->count();
    }

    public function totalCount(): int
    {
        return $this->inner->totalCount();
    }

    public function getIterator(): \Traversable
    {
        return $this->inner->getIterator();
    }
}
