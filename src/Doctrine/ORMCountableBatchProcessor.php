<?php

namespace Zenstruck\Porpaginas\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Marco Pivetta <ocramius@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMCountableBatchProcessor implements \IteratorAggregate, \Countable
{
    private ORMBatchProcessor $batchProcessor;
    private iterable $countable;

    /**
     * @param iterable|\Countable $results
     */
    public function __construct(iterable $results, EntityManagerInterface $em, int $batchSize = 100)
    {
        if (!\is_countable($results)) {
            throw new \InvalidArgumentException('$results must be countable.');
        }

        $iterable = $results;

        if ($results instanceof \IteratorAggregate) {
            $iterable = $results->getIterator();
        }

        $this->batchProcessor = new ORMBatchProcessor($iterable, $em, $batchSize);
        $this->countable = $results;
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
