<?php

namespace Zenstruck\Porpaginas\Doctrine\Repository;

use Zenstruck\Porpaginas\Matchable;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class DBALMatchableObjectRepository extends DBALObjectRepository implements Matchable
{
    public function match($specification): Result
    {
        // TODO: Implement match() method.
    }

    public function matchOne($specification)
    {
        // TODO: Implement matchOne() method.
    }
}
