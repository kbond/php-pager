<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;

class ORMQueryResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $entityManager = $this->setupEntityManager($count);
        $query = $entityManager->createQuery(sprintf('SELECT e FROM %s e', DoctrineOrmEntity::class));

        return new ORMQueryResult($query);
    }
}
