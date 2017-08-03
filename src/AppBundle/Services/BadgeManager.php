<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

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

    public function checkAndUnlockBadge($actionName)
    {
        $badge = $this->em->getRepository('AppBundle:Badge')->findBy(array(
            'actionName' => $actionName,
        ));

        var_dump($badge);

    }
}
