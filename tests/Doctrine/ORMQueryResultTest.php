<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;

class ORMQueryResultTest extends ORMResultTest
{
    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $query = $this->em->createQuery(\sprintf('SELECT e FROM %s e', ORMEntity::class));

        return new ORMQueryResult($query);
    }
}
