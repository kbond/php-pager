<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMCountableBatchProcessor implements \IteratorAggregate, \Countable
{
    private ORMBatchProcessor $batchProcessor;

    /** @var iterable|array|\Countable */
    private iterable $countable;

    /**
     * @param array|\Countable $items
     */
    public function __construct(iterable $items, EntityManagerInterface $em, int $chunkSize = 100)
    {
        if (!\is_countable($items)) {
            throw new \InvalidArgumentException('$items must be countable.');
        }

        $this->batchProcessor = new ORMBatchProcessor($items, $em, $chunkSize);
        $this->countable = &$items;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->batchProcessor;
    }

    public function count(): int
    {
        return \count($this->countable);
    }
}
