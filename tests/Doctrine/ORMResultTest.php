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
}
