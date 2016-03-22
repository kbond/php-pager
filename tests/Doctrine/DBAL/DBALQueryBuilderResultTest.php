<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\DBAL;

use Zenstruck\Porpaginas\Doctrine\DBAL\DBALQueryBuilderResult;
use Zenstruck\Porpaginas\Tests\Doctrine\DoctrineResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DBALQueryBuilderResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $em = $this->setupEntityManager($count);
        $qb = $em->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('DoctrineOrmEntity', 'e');

        return new DBALQueryBuilderResult($qb);
    }
}
