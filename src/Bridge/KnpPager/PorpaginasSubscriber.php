<?php

namespace Zenstruck\Porpaginas\Bridge\KnpPager;

use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenstruck\Porpaginas\Result;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PorpaginasSubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event)
    {
        if (!$event->target instanceof Result) {
            return;
        }

        $event->count = $event->target->count();
        $event->items = $event->target->take($event->getOffset(), $event->getLimit())->getIterator();

        $event->stopPropagation();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return ['knp_pager.items' => ['items', 0]];
    }
}
