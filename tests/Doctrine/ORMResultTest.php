<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

abstract class ORMResultTest extends DoctrineResultTestCase
{
    /**
     * @test
     */
    public function detaches_entity_from_em_on_iterate()
    {
        $result = \iterator_to_array($this->createResultWithItems(2))[0];

        $this->assertFalse($this->em->contains($result));
    }

    /**
     * @test
     */
    public function can_batch_update_results()
    {
        $result = $this->createResultWithItems(2);
        $values = \array_map(function (DoctrineOrmEntity $entity) { return $entity->value; }, \iterator_to_array($result));

        $this->assertSame(['value', 'value'], $values);

        $batchIterator = $result->batchIterator();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $item) {
            $item->value = 'new '.$item->value;
        }

        $values = \array_map(
            function (DoctrineOrmEntity $entity) { return $entity->value; },
            $this->em->getRepository(DoctrineOrmEntity::class)->findAll()
        );

        $this->assertSame(['new value', 'new value'], $values);
    }

    /**
     * @test
     */
    public function can_batch_delete_results()
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);

        $batchIterator = $result->batchIterator();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $item) {
            $this->em->remove($item);
        }

        $this->assertCount(0, $this->em->getRepository(DoctrineOrmEntity::class)->findAll());
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return new DoctrineOrmEntity($position);
    }
}
