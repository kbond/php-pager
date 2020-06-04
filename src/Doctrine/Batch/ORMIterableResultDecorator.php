<?php

namespace Zenstruck\Porpaginas\Doctrine\Batch;

use Doctrine\ORM\Internal\Hydration\IterableResult;

/**
 * Fixes https://github.com/doctrine/orm/issues/2821.
 *
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMIterableResultDecorator implements \IteratorAggregate
{
    private $result;

    public function __construct(IterableResult $result)
    {
        $this->result = $result;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->result as $key => $value) {
            yield $key => self::normalizeResult($value);
        }
    }

    /**
     * @param mixed $result
     *
     * @return mixed
     */
    private static function normalizeResult($result)
    {
        if (!\is_array($result)) {
            return $result;
        }

        $firstKey = \array_key_first($result);

        if (null !== $firstKey && \is_object($result[$firstKey]) && $result === [$firstKey => $result[$firstKey]]) {
            return $result[$firstKey];
        }

        if (\count($result) > 1) {
            $result = [\array_merge(...$result)];
        }

        return $result[$firstKey];
    }
}
