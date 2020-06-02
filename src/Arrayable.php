<?php

namespace Zenstruck\Porpaginas;

/**
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait Arrayable
{
    final public function toArray(): array
    {
        return \iterator_to_array($this);
    }
}
