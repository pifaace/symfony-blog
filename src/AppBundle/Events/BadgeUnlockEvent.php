<?php

namespace AppBundle\Events;

use AppBundle\Entity\Badge;
use AppBundle\Entity\UnlockBadge;
use Symfony\Component\EventDispatcher\Event;

class BadgeUnlockEvent extends Event
{
    const NAME = 'badge.unlock';
    /**
     * @var UnlockBadge
     */
    private $unlockBadge;

    public function __construct(UnlockBadge $unlockBadge)
    {

        $this->unlockBadge = $unlockBadge;
    }

    /**
     * @return UnlockBadge
     */
    public function getUnlockBadge(): UnlockBadge
    {
        return $this->unlockBadge;
    }

}
