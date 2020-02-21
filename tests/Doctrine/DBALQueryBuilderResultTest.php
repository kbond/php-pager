<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\DBALQueryBuilderResult;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DBALQueryBuilderResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $qb = $this->em->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('DoctrineOrmEntity', 'e')
        ;

        return new DBALQueryBuilderResult($qb);
    }

    protected function getExpectedFirstValue()
    {
        return ['id' => 1, 'value' => 'value'];
    }
}
