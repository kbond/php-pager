<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Doctrine\RSMQueryResult;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $em = $this->setupEntityManager($count);
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(DoctrineOrmEntity::class, 'e');

        $qb = $em->getConnection()->createQueryBuilder()
            ->select($rsm->generateSelectClause())
            ->from('DoctrineOrmEntity', 'e');

        return new RSMQueryResult($em, $rsm, $qb);
    }
}
