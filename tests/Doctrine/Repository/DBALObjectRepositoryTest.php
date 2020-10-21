<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObject;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\DBALObjectRepository;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class DBALObjectRepositoryTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_iterate_and_count(): void
    {
        $this->persistEntities(4);

        $repository = new DBALObjectRepository($this->em->getConnection());

        $this->assertCount(4, \iterator_to_array($repository));
        $this->assertCount(4, $repository);
    }

    /**
     * @test
     */
    public function creates_object(): void
    {
        $this->persistEntities(1);

        $object = \iterator_to_array(new DBALObjectRepository($this->em->getConnection()))[0];

        $this->assertInstanceOf(DBALObject::class, $object);
        $this->assertSame('value 1', $object->value);
    }
}
