<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Badge;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: maxime.joassy
 * Date: 03/08/2017
 * Time: 13:02
 */
class LoadBadgeData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $badge = new Badge();
        $badge->setName('Timide');
        $badge->setActionName('comment');
        $badge->setActionCount(1);
        $badge->setDescription('Poster un commentaire');
        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Forceur');
        $badge->setActionName('comment');
        $badge->setActionCount(3);
        $badge->setDescription('Poster trois commentaire');
        $manager->persist($badge);

        $manager->flush();

    }
}
