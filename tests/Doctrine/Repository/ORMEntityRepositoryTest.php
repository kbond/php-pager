<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntityRepository;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMEntityRepositoryTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_iterate_and_count(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $this->assertCount(4, \iterator_to_array($repository));
        $this->assertCount(4, $repository);
    }

    /**
     * @test
     */
    public function can_create_batch_processor(): void
    {
        $this->persistEntities(4);

        $processor = (new ORMEntityRepository($this->em))->batchProcessor();

        $this->assertCount(4, \iterator_to_array($processor));
        $this->assertCount(4, $processor);
    }

    /**
     * @test
     */
    public function calls_are_passed_to_inner_repository(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $entity = $repository->findOneByValue('value 2');

        $this->assertInstanceOf(ORMEntity::class, $entity);
        $this->assertSame('value 2', $entity->value);
    }

    /**
     * @test
     */
    public function can_find(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $entity = $repository->find(1);

        $this->assertInstanceOf(ORMEntity::class, $entity);
        $this->assertSame('value 1', $entity->value);
    }

    /**
     * @test
     */
    public function can_find_all(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $this->assertCount(4, $repository->findAll());
    }

    /**
     * @test
     */
    public function can_find_one_by(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $entity = $repository->findOneBy(['value' => 'value 2']);

        $this->assertInstanceOf(ORMEntity::class, $entity);
        $this->assertSame('value 2', $entity->value);
    }

    /**
     * @test
     */
    public function can_find_one_by_with_order(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $entity = $repository->findOneBy([], ['id' => 'DESC']);

        $this->assertInstanceOf(ORMEntity::class, $entity);
        $this->assertSame('value 4', $entity->value);
    }

    /**
     * @test
     */
    public function can_find_by(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $this->assertCount(1, $repository->findBy(['value' => 'value 2']));
    }

    /**
     * @test
     */
    public function can_match_with_get(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);
        $object = $repository->get(function(QueryBuilder $qb, string $alias) {
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
    public function get_throws_exception_if_no_item_found(): void
    {
        $this->persistEntities(4);

        $repository = new ORMEntityRepository($this->em);

        $this->expectException(NotFound::class);
        $this->expectExceptionMessage(ORMEntity::class.' not found for given specification.');

        $repository->get(function(QueryBuilder $qb, string $alias) {
            $qb->where("{$alias}.value = :value")
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

        $repository = new ORMEntityRepository($this->em);
        $objects = $repository->filter(function(QueryBuilder $qb, string $alias) {
            $qb->where("{$alias}.id > :value")
                ->setParameter('value', 2)
            ;
        });

        $this->assertCount(2, $objects);
        $this->assertSame('value 3', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 4', \iterator_to_array($objects)[1]->value);
    }
}
