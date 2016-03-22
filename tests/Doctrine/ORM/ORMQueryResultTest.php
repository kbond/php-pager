<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\ORM;

use Zenstruck\Porpaginas\Doctrine\ORM\ORMQueryResult;
use Zenstruck\Porpaginas\Tests\Doctrine\DoctrineResultTestCase;

class ORMQueryResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $entityManager = $this->setupEntityManager($count);
        $query = $entityManager->createQuery('SELECT e FROM Zenstruck\Porpaginas\Tests\Doctrine\DoctrineOrmEntity e');

        return new ORMQueryResult($query);
    }
}
