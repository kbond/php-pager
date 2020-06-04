<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Batch;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMBatchProcessor;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ORMBatchProcessorTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_batch_persist_results(): void
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = 'value '.$i;
        }

        $batchProcessor = new ORMBatchProcessor(new ArrayResult($array), $this->em);

        foreach ($batchProcessor as $item) {
            $this->em->persist(new ORMEntity($item));
        }

        $entities = $this->em->getRepository(ORMEntity::class)->findAll();

        $this->assertCount(211, $entities);
        $this->assertSame('value 32', $entities[31]->value);
        $this->assertSame('value 200', $entities[199]->value);
        $this->assertSame('value 211', $entities[210]->value);
    }

    /**
     * @test
     */
    public function can_batch_persist_new_entities(): void
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = new ORMEntity('value '.$i);
        }

        $batchProcessor = new ORMBatchProcessor(new ArrayResult($array), $this->em);

        foreach ($batchProcessor as $item) {
            $this->em->persist($item);
        }

        $entities = $this->em->getRepository(ORMEntity::class)->findAll();

        $this->assertCount(211, $entities);
        $this->assertSame('value 32', $entities[31]->value);
        $this->assertSame('value 200', $entities[199]->value);
        $this->assertSame('value 211', $entities[210]->value);
    }

    /**
     * @test
     */
    public function can_batch_persist_results_from_array(): void
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = 'value '.$i;
        }

        $batchProcessor = new ORMBatchProcessor($array, $this->em);

        foreach ($batchProcessor as $item) {
            $this->em->persist(new ORMEntity($item));
        }

        $entities = $this->em->getRepository(ORMEntity::class)->findAll();

        $this->assertCount(211, $entities);
        $this->assertSame('value 32', $entities[31]->value);
        $this->assertSame('value 200', $entities[199]->value);
        $this->assertSame('value 211', $entities[210]->value);
    }

    /**
     * @test
     */
    public function can_batch_persist_array_of_arrays(): void
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = ['value', $i];
        }

        $batchProcessor = new ORMBatchProcessor($array, $this->em);

        foreach ($batchProcessor as [$value, $id]) {
            $this->em->persist(new ORMEntity($value.' '.$id));
        }

        $entities = $this->em->getRepository(ORMEntity::class)->findAll();

        $this->assertCount(211, $entities);
        $this->assertSame('value 32', $entities[31]->value);
        $this->assertSame('value 200', $entities[199]->value);
        $this->assertSame('value 211', $entities[210]->value);
    }

    /**
     * @test
     */
    public function results_do_not_have_to_be_countable(): void
    {
        $iterator = static function () {
            yield 'foo';
        };
        $batchProcessor = new ORMBatchProcessor($iterator(), $this->em);

        $this->assertFalse(\is_countable($batchProcessor));
        $this->assertSame(['foo'], \iterator_to_array($batchProcessor));
    }
}
