<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Result;

class ORMQueryResultFieldsTest extends DoctrineResultTestCase
{
    /**
     * @test
     */
    public function can_iterate()
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);
        $this->assertSame([
            [
                'id' => 1,
                'my_value' => 'value 1',
            ],
            [
                'id' => 2,
                'my_value' => 'value 2',
            ],
        ], $result->toArray());
    }

    /**
     * @test
     */
    public function can_batch_iterate()
    {
        $result = $this->createResultWithItems(2)->batchIterator();

        $this->assertCount(2, $result);
        $this->assertSame([
            [
                'id' => 1,
                'my_value' => 'value 1',
            ],
            [
                'id' => 2,
                'my_value' => 'value 2',
            ],
        ], \iterator_to_array($result));
    }

    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $query = $this->em->createQuery(\sprintf('SELECT e.id, e.value AS my_value FROM %s e', ORMEntity::class));

        return new ORMQueryResult($query, true, false);
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return [
            'id' => $position,
            'my_value' => 'value '.$position,
        ];
    }
}
