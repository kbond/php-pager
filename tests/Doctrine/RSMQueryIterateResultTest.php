<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Doctrine\RSMQueryIterateResult;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RSMQueryIterateResultTest extends DoctrineResultTestCase
{
    protected function createResultWithItems($count)
    {
        $em = $this->setupEntityManager($count);
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(DoctrineOrmEntity::class, 'e');

        $qb = $em->getConnection()->createQueryBuilder()
            ->select($rsm->generateSelectClause())
            ->from('DoctrineOrmEntity', 'e')
        ;

        return new RSMQueryIterateResult($em, $rsm, $qb);
    }
}
