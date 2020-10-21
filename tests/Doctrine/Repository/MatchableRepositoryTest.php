<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Repository;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Exception\NotFound;
use Zenstruck\Porpaginas\Matchable;
use Zenstruck\Porpaginas\Spec;
use Zenstruck\Porpaginas\Tests\Doctrine\HasEntityManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class MatchableRepositoryTest extends TestCase
{
    use HasEntityManager;

    /**
     * @test
     */
    public function can_match_single_comparison(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::eq('value', 'value 2'));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function can_match_composite_comparison(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(
            Spec::andX(
                Spec::gt('id', 1),
                Spec::lt('id', 3)
            )
        );

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function can_match_is_not_null(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::isNotNull('value'));

        $this->assertCount(3, $objects);
    }

    /**
     * @test
     */
    public function can_use_order_by(): void
    {
        $this->persistEntities(3);

        $objects = \iterator_to_array($this->createRepository()->match(Spec::sortDesc('value')));

        $this->assertSame('value 3', $objects[0]->value);
        $this->assertSame('value 2', $objects[1]->value);
        $this->assertSame('value 1', $objects[2]->value);
    }

    /**
     * @test
     */
    public function can_use_composite_order_by(): void
    {
        $this->persistEntities(3);

        $objects = \iterator_to_array($this->createRepository()->match(
            Spec::andX(
                Spec::gt('id', 1),
                Spec::sortDesc('id')
            )
        ));

        $this->assertCount(2, $objects);
        $this->assertSame('value 3', $objects[0]->value);
        $this->assertSame('value 2', $objects[1]->value);
    }

    /**
     * @test
     */
    public function can_match_one_for_single_comparison(): void
    {
        $this->persistEntities(3);

        $object = $this->createRepository()->matchOne(Spec::eq('value', 'value 2'));

        $this->assertSame('value 2', $object->value);
    }

    /**
     * @test
     */
    public function not_found_exception_found_for_match_one_if_no_result(): void
    {
        $this->persistEntities(3);

        $this->expectException(NotFound::class);

        $this->createRepository()->matchOne(Spec::eq('value', 'value 6'));
    }

    abstract protected function createRepository(): Matchable;
}
