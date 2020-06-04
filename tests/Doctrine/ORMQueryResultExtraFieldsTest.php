<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Doctrine\ORMQueryResult;
use Zenstruck\Porpaginas\Result;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

class ORMQueryResultExtraFieldsTest extends ResultTestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function detaches_entity_from_em_on_iterate(): void
    {
        $result = \iterator_to_array($this->createResultWithItems(2))[0][0];

        $this->assertFalse($this->em->contains($result));
    }

    /**
     * @test
     */
    public function can_batch_update_results(): void
    {
        $result = $this->createResultWithItems(2);
        $values = \array_map(static function(array $row) { return $row[0]->value; }, \iterator_to_array($result));

        $this->assertSame(['value 1', 'value 2'], $values);

        $batchIterator = $result->batchProcessor();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $row) {
            $row[0]->value = 'new '.$row[0]->value;
        }

        $values = \array_map(
            static function(ORMEntity $entity) { return $entity->value; },
            $this->em->getRepository(ORMEntity::class)->findAll()
        );

        $this->assertSame(['new value 1', 'new value 2'], $values);
    }

    /**
     * @test
     */
    public function can_batch_delete_results(): void
    {
        $result = $this->createResultWithItems(2);

        $this->assertCount(2, $result);

        $batchIterator = $result->batchProcessor();

        $this->assertCount(2, $batchIterator);

        foreach ($batchIterator as $item) {
            $this->em->remove($item[0]);
        }

        $this->assertCount(0, $this->em->getRepository(ORMEntity::class)->findAll());
    }

    protected function createResultWithItems(int $count): Result
    {
        $this->persistEntities($count);

        $query = $this->em->createQuery(\sprintf('SELECT e, UPPER(e.value) AS extra FROM %s e', ORMEntity::class));

        return new ORMQueryResult($query);
    }

    protected function getExpectedValueAtPosition(int $position)
    {
        $value = 'value '.$position;

        return [
            0 => new ORMEntity($value, $position),
            'extra' => \mb_strtoupper($value),
        ];
    }
}
