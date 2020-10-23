<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Callback\CallbackPage;
use Zenstruck\Porpaginas\Page;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\ResultPaginator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALQueryBuilderResult implements Result
{
    use ResultPaginator;

    private QueryBuilder $qb;
    private $countModifier;
    private ?int $count = null;

    public function __construct(QueryBuilder $qb, ?callable $countModifier = null)
    {
        $this->qb = $qb;
        $this->countModifier = $countModifier ?: static function(QueryBuilder $qb) {
            return $qb->select('COUNT(*)');
        };
    }

    public function take(int $offset, int $limit): Page
    {
        $qb = clone $this->qb;
        $results = static function($offset, $limit) use ($qb) {
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

        \call_user_func($this->countModifier, $qb);

        return $this->count = $qb->execute()->fetchColumn();
    }

    public function getIterator(): \Traversable
    {
        $stmt = $this->qb->execute();

        while ($data = $stmt->fetch()) {
            yield $data;
        }
    }
}
