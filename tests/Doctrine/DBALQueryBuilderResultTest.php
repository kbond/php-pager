<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\DBALQueryBuilderResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DBALQueryBuilderResultTest extends ResultTestCase
{
    use HasEntityManager;

    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $qb = $this->em->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('ORMEntity', 'e')
        ;

        return new DBALQueryBuilderResult($qb);
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return ['id' => $position, 'value' => 'value '.$position];
    }
}
