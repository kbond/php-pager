<?php

namespace Zenstruck\Porpaginas\Factory;

use Zenstruck\Porpaginas\Page;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FactoryPage implements Page
{
    private $factory;
    private $page;

    public function __construct(callable $factory, Page $page)
    {
        $this->factory = $factory;
        $this->page = $page;
    }

    public function getCurrentOffset(): int
    {
        return $this->page->getCurrentOffset();
    }

    public function getCurrentPage(): int
    {
        return $this->page->getCurrentPage();
    }

    public function getCurrentLimit(): int
    {
        return $this->page->getCurrentLimit();
    }

    public function count(): int
    {
        return $this->page->count();
    }

    public function totalCount(): int
    {
        return $this->page->totalCount();
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->page as $result) {
            yield \call_user_func($this->factory, $result);
        }
    }
}
