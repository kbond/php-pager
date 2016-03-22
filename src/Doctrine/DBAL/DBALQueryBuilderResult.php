<?php

namespace Zenstruck\Porpaginas\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALQueryBuilderResult implements Result
{
    private $qb;
    private $count;

    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function take($offset, $limit)
    {
        $qb = clone $this->qb;
        $results = function ($offset, $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->execute()
                ->fetchAll();
        };

        return new CallbackPage($results, [$this, 'count'], $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->count !== null) {
            return $this->count;
        }

        $qb = clone $this->qb;
        $stmt = $qb->select('COUNT(*) as cnt')
            ->orderBy('cnt')
            ->execute();

        return $this->count = (int) $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $stmt = $this->qb->execute();

        while ($data = $stmt->fetch()) {
            yield $data;
        }
    }
}
