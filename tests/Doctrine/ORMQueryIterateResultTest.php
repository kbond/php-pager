<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryIterateResult;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMQueryIterateResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $entityManager = $this->setupEntityManager($count);
        $query = $entityManager->createQuery(sprintf('SELECT e FROM %s e', DoctrineOrmEntity::class));

        return new ORMQueryIterateResult($entityManager, $query);
    }
}
