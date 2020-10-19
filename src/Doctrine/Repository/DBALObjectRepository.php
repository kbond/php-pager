<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\DBALQueryBuilderResult;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Factory\FactoryResult;
use Zenstruck\Porpaginas\Repository;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class DBALObjectRepository implements Repository
{
    public function getIterator(): \Traversable
    {
        return static::createResult($this->qb());
    }

    public function count(): int
    {
        return \count(static::createResult($this->qb()));
    }

    /**
     * @param callable(QueryBuilder $qb) $specification
     */
    public function get(callable $specification): object
    {
        $result = $this->queryBuilderForSpecification($specification)
            ->setMaxResults(1)
            ->execute()
            ->fetch()
        ;

        if (!$result) {
            throw new NotFound(\sprintf('Object from "%s" table not found for given specification.', static::tableName()));
        }

        return static::createObject($result);
    }

    /**
     * @param callable(QueryBuilder $qb) $specification
     */
    public function filter(callable $specification): Result
    {
        return self::createResult($this->queryBuilderForSpecification($specification));
    }

    abstract protected static function createObject(array $data): object;

    abstract protected static function tableName(): string;

    abstract protected function connection(): Connection;

    final protected static function createResult(QueryBuilder $qb): Result
    {
        return new FactoryResult(
            static function(array $data) {
                return static::createObject($data);
            },
            static::createDBALQueryBuilderResult($qb)
        );
    }

    protected static function createDBALQueryBuilderResult(QueryBuilder $qb): DBALQueryBuilderResult
    {
        return new DBALQueryBuilderResult($qb);
    }

    protected function qb(): QueryBuilder
    {
        return $this->connection()->createQueryBuilder()->select('*')->from(static::tableName());
    }

    private function queryBuilderForSpecification(callable $specification): QueryBuilder
    {
        $specification($qb = $this->qb());

        return $qb;
    }
}
