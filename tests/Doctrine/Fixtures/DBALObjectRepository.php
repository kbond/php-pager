<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Fixtures;

use Doctrine\DBAL\Connection;
use Zenstruck\Porpaginas\Doctrine\Repository\DBALMatchableObjectRepository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALObjectRepository extends DBALMatchableObjectRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected static function createObject(array $data): object
    {
        return new DBALObject($data['value']);
    }

    protected static function tableName(): string
    {
        return 'ORMEntity';
    }

    protected function connection(): Connection
    {
        return $this->connection;
    }
}
