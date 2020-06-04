<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Batch;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Doctrine\Batch\ORMCountableBatchProcessor;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ORMCountableBatchProcessorTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_batch_persist_results()
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = 'value '.$i;
        }

        $batchProcessor = new ORMCountableBatchProcessor(new ArrayResult($array), $this->em);

        $this->assertTrue(\is_countable($batchProcessor));
        $this->assertCount(211, $batchProcessor);

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
    public function can_batch_persist_new_entities()
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = new ORMEntity('value '.$i);
        }

        $batchProcessor = new ORMCountableBatchProcessor(new ArrayResult($array), $this->em);

        $this->assertTrue(\is_countable($batchProcessor));
        $this->assertCount(211, $batchProcessor);

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
    public function can_batch_persist_results_from_array()
    {
        $array = [];

        for ($i = 1; $i <= 211; ++$i) {
            $array[] = 'value '.$i;
        }

        $batchProcessor = new ORMCountableBatchProcessor($array, $this->em);

        $this->assertTrue(\is_countable($batchProcessor));
        $this->assertCount(211, $batchProcessor);

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
    public function results_must_be_countable()
    {
        $iterator = function () {
            yield 'foo';
        };

        $this->expectException(\InvalidArgumentException::class);

        new ORMCountableBatchProcessor($iterator(), $this->em);
    }
}
