<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ORMQueryBuilderResultTest extends ORMResultTest
{
    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from(ORMEntity::class, 'e')
        ;

        return new ORMQueryResult($qb);
    }
}
