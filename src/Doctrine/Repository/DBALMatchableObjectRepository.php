<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\DBAL\Specification\DBALContext;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Matchable;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class DBALMatchableObjectRepository extends DBALObjectRepository implements Matchable
{
    final public function match($specification): Result
    {
        return static::createResult($this->qbForSpecification($specification));
    }

    final public function matchOne($specification)
    {
        $result = $this->qbForSpecification($specification)
            ->setMaxResults(1)
            ->execute()
            ->fetch()
        ;

        if (!$result) {
            throw new NotFound(\sprintf('Object from "%s" table not found for given specification.', static::tableName()));
        }

        return static::createObject($result);
    }

    final protected function qbForSpecification($specification): QueryBuilder
    {
        $qb = $this->qb();
        $result = $this->specificationNormalizer()->normalize($specification, new DBALContext($qb));

        if ($result) {
            $qb->where($result);
        }

        return $qb;
    }

    /**
     * Override to provide your own SpecificationNormalizer implementation.
     */
    protected function specificationNormalizer(): Normalizer
    {
        return DBALContext::defaultNormalizer();
    }
}
