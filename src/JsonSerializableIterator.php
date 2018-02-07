<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @mixin \IteratorAggregate
 */
trait JsonSerializableIterator
{
    public function jsonSerialize(): array
    {
        return \iterator_to_array($this->getIterator());
    }
}
