<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Zenstruck\Porpaginas\JsonSerializableIterator;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMQueryIterateResult implements Result
{
    use JsonSerializableIterator;

    private $em;
    private $child;

    /**
     * @param Query|QueryBuilder $query
     */
    public function __construct(EntityManagerInterface $em, $query, bool $fetchCollection = true)
    {
        $this->em = $em;
        $this->child = new ORMQueryResult($query, $fetchCollection);
    }

    public function take(int $offset, int $limit): Page
    {
        return $this->child->take($offset, $limit);
    }

    public function count(): int
    {
        return $this->child->count();
    }

    public function getIterator(): \Iterator
    {
        foreach ($this->child->getQuery()->iterate() as $row) {
            yield $row[0];

            $this->em->detach($row[0]);
        }
    }
}
