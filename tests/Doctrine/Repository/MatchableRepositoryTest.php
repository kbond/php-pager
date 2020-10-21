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
    public function match_and_x_composite(): void
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
    public function match_or_x_composite(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(
            Spec::orX(
                Spec::lt('id', 2),
                Spec::gt('id', 2)
            )
        );

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_like(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::like('value', 'value 2'));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_like_wildcard(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::like('value', 'value *')->allowWildcard());

        $this->assertCount(3, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 2', \iterator_to_array($objects)[1]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[2]->value);
    }

    /**
     * @test
     */
    public function match_contains(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::contains('value', 'value'));

        $this->assertCount(3, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 2', \iterator_to_array($objects)[1]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[2]->value);
    }

    /**
     * @test
     */
    public function match_begins_with(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::beginsWith('value', 'v'));

        $this->assertCount(3, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 2', \iterator_to_array($objects)[1]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[2]->value);
    }

    /**
     * @test
     */
    public function match_ends_with(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::endsWith('value', '2'));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_not_like(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notLike('value', 'value 2'));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_not_like_wildcard(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notLike('value', 'value *')->allowWildcard());

        $this->assertEmpty($objects);
    }

    /**
     * @test
     */
    public function match_not_contains(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notContains('value', 'value'));

        $this->assertEmpty($objects);
    }

    /**
     * @test
     */
    public function match_not_beginning_with(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notBeginningWith('value', 'value'));

        $this->assertEmpty($objects);
    }

    /**
     * @test
     */
    public function match_not_ends_with(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notEndingWith('value', '2'));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_equal(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::eq('value', 'value 2'));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_not_equal(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::neq('value', 'value 2'));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_is_null(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::isNull('value'));

        $this->assertEmpty($objects);
    }

    /**
     * @test
     */
    public function match_is_not_null(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::isNotNull('value'));

        $this->assertCount(3, $objects);
    }

    /**
     * @test
     */
    public function match_in_string(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::in('value', ['value 1', 'value 3']));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_in_int(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::in('id', [1, 3]));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_in_numeric_string(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::in('id', ['1', '3']));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_in_mixed_str_field(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::in('value', ['1', 'value 2', 3]));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_in_mixed_int_field(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::in('id', ['1', 'value 2', 3]));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_not_in_string(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notIn('value', ['value 1', 'value 3']));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_not_in_int(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notIn('id', [1, 3]));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_not_in_numeric_string(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notIn('id', ['1', '3']));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_not_in_mixed_str_field(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notIn('value', ['1', 'value 2', 3]));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_not_in_mixed_int_field(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::notIn('id', ['1', 'value 2', 3]));

        $this->assertCount(1, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
    }

    /**
     * @test
     */
    public function match_less_than(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::lt('id', 3));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 2', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_less_than_equal(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::lte('id', 2));

        $this->assertCount(2, $objects);
        $this->assertSame('value 1', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 2', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_greater_than(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::gt('id', 1));

        $this->assertCount(2, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_greater_than_equal(): void
    {
        $this->persistEntities(3);

        $objects = $this->createRepository()->match(Spec::gte('id', 2));

        $this->assertCount(2, $objects);
        $this->assertSame('value 2', \iterator_to_array($objects)[0]->value);
        $this->assertSame('value 3', \iterator_to_array($objects)[1]->value);
    }

    /**
     * @test
     */
    public function match_sort_desc(): void
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
    public function match_sort_asc(): void
    {
        $this->persistEntities(3);

        $objects = \iterator_to_array($this->createRepository()->match(Spec::sortAsc('value')));

        $this->assertSame('value 1', $objects[0]->value);
        $this->assertSame('value 2', $objects[1]->value);
        $this->assertSame('value 3', $objects[2]->value);
    }

    /**
     * @test
     */
    public function match_composite_order_by(): void
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
    public function match_one_for_single_comparison(): void
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
