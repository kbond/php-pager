<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALQueryBuilderResult implements Result
{
    private $qb;
    private $countQueryBuilderModifier;
    private $count;

    public function __construct(QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->qb = $qb;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier ?: function (QueryBuilder $qb) {
            return $qb->select('COUNT(*)');
        };
    }

    public function take(int $offset, int $limit): Page
    {
        $qb = clone $this->qb;
        $results = function ($offset, $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->execute()
                ->fetchAll()
            ;
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    public function count(): int
    {
        if (null !== $this->count) {
            return $this->count;
        }

        $qb = clone $this->qb;

        \call_user_func($this->countQueryBuilderModifier, $qb);

        return $this->count = (int) $qb->execute()->fetchColumn();
    }

    public function getIterator(): \Iterator
    {
        $stmt = $this->qb->execute();

        while ($data = $stmt->fetch()) {
            yield $data;
        }
    }
}
