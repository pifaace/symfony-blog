<?php

namespace AppBundle\Services;

use AppBundle\Entity\UnlockBadge;
use AppBundle\Events\BadgeUnlockEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BadgeManager
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $actionName
     * @param $actionCount
     * @param $user
     */
    public function checkAndUnlockBadge($actionName, $actionCount, $user)
    {
        try {
            $badge = $this->em
                ->getRepository('AppBundle:Badge')
                ->findBadgeAndNotUsedForUser($actionName, $actionCount, $user->getId());
            if ($badge->getUnlockBadge()->isEmpty()) {
                $unlockBadge = new UnlockBadge();
                $unlockBadge->setBadge($badge);
                $unlockBadge->setUser($user);
                $this->em->persist($unlockBadge);
                $this->em->flush();

                $this->dispatcher->dispatch(BadgeUnlockEvent::NAME, new BadgeUnlockEvent($unlockBadge));
            }
        } catch (NoResultException $e) {
        }
    }

    /**
     * @param $userId
     * @return array
     */
    public function findBadgeFor($userId): array
    {
        $badges = $this->em->getRepository('AppBundle:Badge')->findBadgeFor($userId);

        return $badges;
    }
}
