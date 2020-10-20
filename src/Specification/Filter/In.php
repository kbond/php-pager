<?php

namespace Zenstruck\Porpaginas\Specification\Filter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class In extends Comparison
{
    public function __construct(string $field, array $value)
    {
        parent::__construct($field, $value);
    }

    public function value(): array
    {
        return parent::value();
    }
}
