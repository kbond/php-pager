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
    private $countQueryBuilderModifier;
    private $count;

    /**
     * @param QueryBuilder  $qb
     * @param callable|null $countQueryBuilderModifier
     */
    public function __construct(QueryBuilder $qb, callable $countQueryBuilderModifier = null)
    {
        $this->qb = $qb;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier ?: function (QueryBuilder $qb) {
            return $qb->select('COUNT(*)');
        };
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

        call_user_func($this->countQueryBuilderModifier, $qb);

        return $this->count = (int) $qb->execute()->fetchColumn();
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
