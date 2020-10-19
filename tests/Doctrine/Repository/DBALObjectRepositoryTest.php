<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObject;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObjectRepository;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALObjectRepositoryTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_iterate_and_count(): void
    {
        $this->persistEntities(4);

        $repository = new DBALObjectRepository($this->em->getConnection());

        $this->assertCount(4, \iterator_to_array($repository));
        $this->assertCount(4, $repository);
    }

    /**
     * @test
     */
    public function creates_object(): void
    {
        $this->persistEntities(1);

        $object = \iterator_to_array(new DBALObjectRepository($this->em->getConnection()))[0];

        $this->assertInstanceOf(DBALObject::class, $object);
        $this->assertSame('value 1', $object->value);
    }

    /**
     * @test
     */
    public function can_match_with_get(): void
    {
        $this->persistEntities(4);

        $repository = new DBALObjectRepository($this->em->getConnection());
        $object = $repository->get(function(QueryBuilder $qb) {
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
    public function get_throws_exception_if_no_item_found(): void
    {
        $this->persistEntities(4);

        $repository = new DBALObjectRepository($this->em->getConnection());

        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Object from "ORMEntity" table not found for given specification.');

        $repository->get(function(QueryBuilder $qb) {
            $qb->where('value = :value')
                ->setParameter('value', 'invalid')
            ;
        });
    }

    /**
     * @test
     */
    public function can_match_with_filter(): void
    {
        $this->persistEntities(4);

        $repository = new DBALObjectRepository($this->em->getConnection());
        $objects = $repository->filter(function(QueryBuilder $qb) {
            $qb->where('id > :value')
                ->setParameter('value', 2)
            ;
        });

        $this->assertCount(2, $objects);
        $this->assertSame('value 3', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 4', \iterator_to_array($objects)[1]->value);
    }
}
