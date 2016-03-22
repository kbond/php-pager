<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\ORM;

use Zenstruck\Porpaginas\Doctrine\ORM\ORMQueryResult;
use Zenstruck\Porpaginas\Tests\Doctrine\DoctrineResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ORMQueryBuilderResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $entityManager = $this->setupEntityManager($count);
        $qb = $entityManager->createQueryBuilder()
            ->select('e')
            ->from('Zenstruck\Porpaginas\Tests\Doctrine\DoctrineOrmEntity', 'e');

        return new ORMQueryResult($qb);
    }

}
