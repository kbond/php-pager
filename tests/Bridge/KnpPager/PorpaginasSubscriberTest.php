<?php

namespace Zenstruck\Porpaginas\Tests\Bridge\KnpPager;

use Knp\Component\Pager\Event\ItemsEvent;
use Knp\Component\Pager\Paginator;
use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Bridge\KnpPager\PorpaginasSubscriber;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PorpaginasSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function it_counts_total_number_of_results()
    {
        $paginator = new Paginator();
        $paginator->subscribe(new PorpaginasSubscriber());
        $result = new ArrayResult([1, 2, 3, 4]);

        $this->assertEquals(4, $paginator->paginate($result, 1, 2)->getTotalItemCount());
    }

    /**
     * @test
     */
    public function it_iterates_slice()
    {
        $paginator = new Paginator();
        $paginator->subscribe(new PorpaginasSubscriber());
        $result = new ArrayResult([1, 2, 3, 4]);

        $results = $paginator->paginate($result, 1, 2);

        $this->assertEquals([1, 2], \iterator_to_array($results));

        $results = $paginator->paginate($result, 2, 2);

        $this->assertEquals([3, 4], \iterator_to_array($results));
    }

    /**
     * @test
     */
    public function it_skips_if_not_instance_of_result()
    {
        $subscriber = new PorpaginasSubscriber();
        $event = new ItemsEvent(1, 2);

        $this->assertNull($event->target);

        $subscriber->items($event);

        $this->assertNull($event->target);
    }
}
