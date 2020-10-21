<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMCountableBatchProcessor;
use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Repository;

/**
 * @mixin EntityRepository
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ORMRepository implements ObjectRepository, Repository
{
    private const DEFAULT_ALIAS = 'entity';

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
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        return $this->repo()->findOneBy($criteria, $orderBy);
    }

    /**
     * @param callable(QueryBuilder $qb, string $alias) $specification
     */
    final public function get(callable $specification)
    {
        if (null === $result = $this->queryForSpecification($specification)->getOneOrNullResult()) {
            throw new NotFound("{$this->getClassName()} not found for given specification.");
        }

        return $result;
    }

    /**
     * @param callable(QueryBuilder $qb, string $alias) $specification
     */
    final public function filter(callable $specification): ORMQueryResult
    {
        return self::createResult($this->queryForSpecification($specification));
    }

    /**
     * @param Query|QueryBuilder $query
     */
    final protected static function createResult($query): ORMQueryResult
    {
        return new ORMQueryResult($query);
    }

    final protected function qb(string $alias = self::DEFAULT_ALIAS, ?string $indexBy = null): QueryBuilder
    {
        return $this->repo()->createQueryBuilder($alias, $indexBy);
    }

    final protected function repo(): EntityRepository
    {
        return $this->repo ?: $this->repo = static::createEntityRepository($this->em(), $this->em()->getClassMetadata($this->getClassName()));
    }

    abstract protected function em(): EntityManagerInterface;

    protected static function createEntityRepository(EntityManagerInterface $em, ClassMetadata $class): EntityRepository
    {
        return new EntityRepository($em, $class);
    }

    private function queryForSpecification(callable $specification): Query
    {
        $specification($qb = $this->qb(self::DEFAULT_ALIAS), self::DEFAULT_ALIAS);

        return $qb->getQuery();
    }
}
