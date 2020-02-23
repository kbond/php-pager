<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Result;

class ORMQueryResultTest extends ORMResultTest
{
    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $query = $this->em->createQuery(\sprintf('SELECT e FROM %s e', ORMEntity::class));

        return new ORMQueryResult($query);
    }
}
