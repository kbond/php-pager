<?php

namespace Zenstruck\Porpaginas\Tests\Specification\Filter;

use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Specification\Filter\Like;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class LikeTest extends TestCase
{
    /**
     * @test
     * @dataProvider likeValueDataProvider
     */
    public function like_value(Like $specification, $expectedValue): void
    {
        $this->assertSame($expectedValue, $specification->value());
    }

    public static function likeValueDataProvider(): iterable
    {
        yield [new Like('field', null), ''];
        yield [new Like('field', 'foo'), 'foo'];
        yield [new Like('field', '%'), '%'];
        yield [new Like('field', '%fo%o%'), '%fo%o%'];
        yield [new Like('field', '%fo*o%'), '%fo*o%'];
        yield [(new Like('field', '%fo*o%'))->allowWildcard(), '%fo%o%'];
        yield [(new Like('field', '%fo&o%'))->allowWildcard('&'), '%fo%o%'];

        yield [Like::contains('field', null), '%%'];
        yield [Like::contains('field', 'foo'), '%foo%'];
        yield [Like::contains('field', '%'), '%%%'];
        yield [Like::contains('field', 'fo%o'), '%fo%o%'];
        yield [Like::contains('field', 'fo*o'), '%fo*o%'];
        yield [Like::contains('field', 'fo*o')->allowWildcard(), '%fo%o%'];
        yield [Like::contains('field', 'fo&o')->allowWildcard('&'), '%fo%o%'];

        yield [Like::beginsWith('field', null), '%'];
        yield [Like::beginsWith('field', 'foo'), 'foo%'];
        yield [Like::beginsWith('field', '%'), '%%'];
        yield [Like::beginsWith('field', 'fo%o'), 'fo%o%'];
        yield [Like::beginsWith('field', 'fo*o'), 'fo*o%'];
        yield [Like::beginsWith('field', 'fo*o')->allowWildcard(), 'fo%o%'];
        yield [Like::beginsWith('field', 'fo&o')->allowWildcard('&'), 'fo%o%'];

        yield [Like::endsWith('field', null), '%'];
        yield [Like::endsWith('field', 'foo'), '%foo'];
        yield [Like::endsWith('field', '%'), '%%'];
        yield [Like::endsWith('field', 'fo%o'), '%fo%o'];
        yield [Like::endsWith('field', 'fo*o'), '%fo*o'];
        yield [Like::endsWith('field', 'fo*o')->allowWildcard(), '%fo%o'];
        yield [Like::endsWith('field', 'fo&o')->allowWildcard('&'), '%fo%o'];
    }
}
