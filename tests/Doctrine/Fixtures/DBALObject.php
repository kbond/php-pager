<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Fixtures;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALObject
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
