<?php

namespace Zenstruck\Porpaginas\Doctrine\ORM;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Zenstruck\Porpaginas\Page;

class ORMQueryPage implements Page
{
    private $paginator;
    private $result;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentOffset()
    {
        return $this->paginator->getQuery()->getFirstResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        return floor($this->getCurrentOffset() / $this->getCurrentLimit()) + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentLimit()
    {
        return $this->paginator->getQuery()->getMaxResults();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->result === null) {
            $this->result = iterator_to_array($this->paginator);
        }

        return count($this->result);
    }

    /**
     * {@inheritdoc}
     */
    public function totalCount()
    {
        return $this->paginator->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->result !== null) {
            return new \ArrayIterator($this->result);
        }

        return $this->paginator->getIterator();
    }
}
