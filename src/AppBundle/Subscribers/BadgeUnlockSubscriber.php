<?php

namespace AppBundle\Subscribers;

use AppBundle\Events\BadgeUnlockEvent;
use AppBundle\Services\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeUnlockSubscriber implements EventSubscriberInterface
{

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

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
        return $this->mailer->unlockedBadgeEmail($event->getBadge(), $event->getUser());
    }
}
