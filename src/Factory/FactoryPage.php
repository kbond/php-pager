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

    /**
     * {@inheritdoc}
     */
    public function getCurrentOffset()
    {
        return $this->page->getCurrentOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        return $this->page->getCurrentPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentLimit()
    {
        return $this->page->getCurrentLimit();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->page->count();
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        return $this->page->totalCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        foreach ($this->page as $result) {
            yield call_user_func($this->factory, $result);
        }
    }
}
