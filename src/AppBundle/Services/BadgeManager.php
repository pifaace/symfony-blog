<?php

namespace AppBundle\Services;

use AppBundle\Entity\UnlockBadge;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * Created by PhpStorm.
 * User: maxime.joassy
 * Date: 03/08/2017
 * Time: 15:41
 */
class BadgeManager
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function checkAndUnlockBadge($actionName, $actionCount, $user)
    {
        try {
            $badge = $this->em
                ->getRepository('AppBundle:Badge')
                ->badgeUnlockAndNotUsed($actionName, $actionCount, $user->getId());
            if ($badge->getUnlockBadge()->isEmpty()) {
                $unlockBadge = new UnlockBadge();
                $unlockBadge->setBadge($badge);
                $unlockBadge->setUser($user);
                $this->em->persist($unlockBadge);
                $this->em->flush();
            }
        } catch (NoResultException $e) {
        }
    }
}
