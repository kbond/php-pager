<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObject;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObjectRepository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALMatchableObjectRepositoryTest extends MatchableRepositoryTest
{
    /**
     * TODO remove.
     *
     * @before
     */
    public function incomplete()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_match_one_with_callable(): void
    {
        $this->persistEntities(4);

        $object = $this->createRepository()->matchOne(function(QueryBuilder $qb) {
            $qb->where('value = :value')
                ->setParameter('value', 'value 2')
            ;
        });

        $this->assertInstanceOf(DBALObject::class, $object);
        $this->assertSame('value 2', $object->value);
    }

    /**
     * @test
     */
    public function can_match_with_callable(): void
    {
        $this->persistEntities(4);

        $objects = $this->createRepository()->match(function(QueryBuilder $qb) {
            $qb->where('id > :value')
                ->setParameter('value', 2)
            ;
        });

        $this->assertCount(2, $objects);
        $this->assertSame('value 3', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 4', \iterator_to_array($objects)[1]->value);
    }

    protected function createRepository(): DBALObjectRepository
    {
        return new DBALObjectRepository($this->em->getConnection());
    }
}
