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
}
