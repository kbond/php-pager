<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMCountableBatchProcessor;
use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Repository;

/**
 * @mixin EntityRepository
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ORMRepository implements ObjectRepository, Repository
{
    private ?EntityManagerInterface $em = null;
    private ?EntityRepository $repo = null;

    final public function __call($name, $arguments)
    {
        return $this->repo()->{$name}(...$arguments);
    }

    public function getIterator(): \Traversable
    {
        return $this->batchIterator();
    }

    public function batchIterator(int $chunkSize = 100): \Traversable
    {
        return static::createResult($this->qb())->batchIterator($chunkSize);
    }

    public function batchProcessor(int $chunkSize = 100): ORMCountableBatchProcessor
    {
        return static::createResult($this->qb())->batchProcessor($chunkSize);
    }

    public function count(): int
    {
        return \count(static::createResult($this->qb()));
    }

    /**
     * @see EntityRepository::find()
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        return $this->repo()->find($id, $lockMode, $lockVersion);
    }

    /**
     * @see EntityRepository::findAll()
     */
    public function findAll(): array
    {
        return $this->repo()->findAll();
    }

    /**
     * @see EntityRepository::findBy()
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repo()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @see EntityRepository::findOneBy()
     */
    public function findOneBy(array $criteria): ?object
    {
        return $this->repo()->findOneBy($criteria);
    }

    final protected static function createResult(QueryBuilder $qb): ORMQueryResult
    {
        return new ORMQueryResult($qb);
    }

    final protected function qb(string $alias = 'entity', string $indexBy = null): QueryBuilder
    {
        return $this->repo()->createQueryBuilder($alias, $indexBy);
    }

    /**
     * @return EntityManagerInterface
     */
    final protected function em(): ObjectManager
    {
        if ($this->em) {
            return $this->em;
        }

        return $this->em = $this->managerRegistry()->getManagerForClass($this->getClassName());
    }

    /**
     * @return EntityRepository
     */
    final protected function repo(): ObjectRepository
    {
        return $this->repo ?: $this->repo = static::createEntityRepository($this->em(), $this->em()->getClassMetadata($this->getClassName()));
    }

    abstract protected function managerRegistry(): ManagerRegistry;

    protected static function createEntityRepository(EntityManagerInterface $em, ClassMetadata $class): EntityRepository
    {
        return new EntityRepository($em, $class);
    }
}
