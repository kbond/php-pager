<?php

namespace Zenstruck\Porpaginas\Doctrine;

/**
 * Fixes https://github.com/doctrine/orm/issues/2821.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class IterableQueryResultNormalizer
{
    /**
     * @param mixed $result
     *
     * @return mixed
     */
    public static function normalize($result)
    {
        if (!\is_array($result)) {
            return $result;
        }

        $firstKey = \key($result);

        if (null !== $firstKey && \is_object($result[$firstKey]) && $result === [$firstKey => $result[$firstKey]]) {
            return $result[$firstKey];
        }

        if (\is_array($result) && 1 < \count($result)) {
            $result = [\array_merge(...$result)];
        }

        return $result[0];
    }
}
