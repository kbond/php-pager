<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Doctrine\ORMBatchProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ORMBatchProcessorTest extends TestCase
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
    public function can_batch_persist_new_entities()
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
    public function can_batch_persist_results_from_array()
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
    public function results_do_not_have_to_be_countable()
    {
        $iterator = function () {
            yield 'foo';
        };
        $batchProcessor = new ORMBatchProcessor($iterator(), $this->em);

        $this->assertFalse(\is_countable($batchProcessor));
        $this->assertSame(['foo'], \iterator_to_array($batchProcessor));
    }
}
