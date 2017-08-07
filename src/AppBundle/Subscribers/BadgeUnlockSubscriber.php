<?php

namespace AppBundle\Subscribers;

use AppBundle\Events\BadgeUnlockEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeUnlockSubscriber implements EventSubscriberInterface
{

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
           BadgeUnlockEvent::NAME => 'onBadgeUnlock'
        );
    }

    public function onBadgeUnlock(BadgeUnlockEvent $event)
    {
        // Envoyer une notification
    }
}
