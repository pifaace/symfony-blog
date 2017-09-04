<?php
/**
 * Created by PhpStorm.
 * User: maxime.joassy
 * Date: 03/08/2017
 * Time: 15:15
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('Une annonce de test');
        $article->setAuthor($this->getReference('user1'));
        $article->setContent('Ceci est une annonce pour faire des petits tests');

        $manager->persist($article);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
