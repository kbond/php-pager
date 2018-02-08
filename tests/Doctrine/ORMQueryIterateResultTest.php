<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryIterateResult;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMQueryIterateResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems(int $count): Result
    {
        $entityManager = $this->setupEntityManager($count);
        $query = $entityManager->createQuery(\sprintf('SELECT e FROM %s e', DoctrineOrmEntity::class));

        return new ORMQueryIterateResult($entityManager, $query);
    }
}
