<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\ORM;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zenstruck\Porpaginas\Doctrine\ORM\RSMQueryResult;
use Zenstruck\Porpaginas\Tests\Doctrine\DoctrineOrmEntity;
use Zenstruck\Porpaginas\Tests\Doctrine\DoctrineResultTestCase;

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
