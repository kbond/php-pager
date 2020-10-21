<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntityRepository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMMatchableRepositoryTest extends MatchableRepositoryTest
{
    /**
     * @test
     */
    public function can_match_one_with_callable(): void
    {
        $this->persistEntities(4);

        $object = $this->createRepository()->matchOne(function(QueryBuilder $qb, string $alias) {
            $qb->where("{$alias}.value = :value")
                ->setParameter('value', 'value 2')
            ;
        });

        $this->assertInstanceOf(ORMEntity::class, $object);
        $this->assertSame('value 2', $object->value);
    }

    /**
     * @test
     */
    public function can_match_with_callable(): void
    {
        $this->persistEntities(4);

        $objects = $this->createRepository()->match(function(QueryBuilder $qb, string $alias) {
            $qb->where("{$alias}.id > :value")
                ->setParameter('value', 2)
            ;
        });

        $this->assertCount(2, $objects);
        $this->assertSame('value 3', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 4', \iterator_to_array($objects)[1]->value);
    }

    protected function createRepository(): ORMEntityRepository
    {
        return new ORMEntityRepository($this->em);
    }
}
