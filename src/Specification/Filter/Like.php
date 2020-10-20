<?php

namespace Zenstruck\Porpaginas\Specification\Filter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Like extends Comparison
{
    private string $format = '%s';
    private ?string $wildcard = null;

    public static function contains(string $field, $value): self
    {
        $specification = new self($field, $value);

        $specification->format = '%%%s%%';

        return $specification;
    }

    public static function beginsWith(string $field, $value): self
    {
        $specification = new self($field, $value);

        $specification->format = '%s%%';

        return $specification;
    }

    public static function endsWith(string $field, $value): self
    {
        $specification = new self($field, $value);

        $specification->format = '%%%s';

        return $specification;
    }

    public function value(): string
    {
        $value = \sprintf($this->format, parent::value());

        if ($this->wildcard) {
            $value = \str_replace($this->wildcard, '%', $value);
        }

        return $value;
    }

    public function allowWildcard(string $wildcard = '*'): self
    {
        $this->wildcard = $wildcard;

        return $this;
    }
}
