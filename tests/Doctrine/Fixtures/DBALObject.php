<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Fixtures;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALObject
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
