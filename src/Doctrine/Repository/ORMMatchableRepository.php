<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use Zenstruck\Porpaginas\Doctrine\ORM\Specification\ORMContext;
use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Matchable;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Specification\Normalizer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ORMMatchableRepository extends ORMRepository implements Matchable
{
    final public function match($specification): Result
    {
        return new ORMQueryResult($this->qbForSpecification($specification));
    }

    final public function matchOne($specification)
    {
        if (!$result = $this->qbForSpecification($specification)->getQuery()->getOneOrNullResult()) {
            throw new NotFound("{$this->getClassName()} not found for given specification.");
        }

        return $result;
    }

    final protected function qbForSpecification($specification): QueryBuilder
    {
        $qb = $this->qb('entity');
        $result = $this->specificationNormalizer()->normalize($specification, new ORMContext($qb, 'entity'));

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
        return ORMContext::defaultNormalizer();
    }
}
