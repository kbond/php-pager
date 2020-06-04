<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\DBALQueryBuilderResult;
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

    abstract protected static function createObject(array $data): object;

    abstract protected static function tableName(): string;

    abstract protected function connection(): Connection;

    final protected static function createResult(QueryBuilder $qb): Result
    {
        return new FactoryResult(
            static function (array $data) {
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
}
