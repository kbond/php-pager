<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

abstract class ORMResultTest extends ResultTestCase
{
    use HasEntityManager;

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
        $values = \array_map(function (ORMEntity $entity) { return $entity->value; }, \iterator_to_array($result));

        $this->assertSame(['value 1', 'value 2'], $values);

        $batchIterator = $result->batchProcessor();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $item) {
            $item->value = 'new '.$item->value;
        }

        $values = \array_map(
            function (ORMEntity $entity) { return $entity->value; },
            $this->em->getRepository(ORMEntity::class)->findAll()
        );

        $this->assertSame(['new value 1', 'new value 2'], $values);
    }

    /**
     * @test
     */
    public function can_batch_delete_results()
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);

        $batchIterator = $result->batchProcessor();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $item) {
            $this->em->remove($item);
        }

        $this->assertCount(0, $this->em->getRepository(ORMEntity::class)->findAll());
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        return new ORMEntity('value '.$position, $position);
    }
}
