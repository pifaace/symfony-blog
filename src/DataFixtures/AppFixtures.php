<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadArticles($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword($this->passwordEncoder->encodePassword($userAdmin, 'password'));
        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setRole(['ROLE_ADMIN']);

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('admin-user', $userAdmin);
    }

    public function loadArticles(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('An article test for my blog');
        $article->setContent($this->getContent());
        $article->setCreateAt();
        $article->setAuthor($this->getReference('admin-user'));

        $manager->persist($article);
        $manager->flush();

        $this->addReference('article', $article);
    }

    public function getContent()
    {
        $faker = Faker\Factory::create();
        return $faker->text($maxNbChars = 600);
    }
}
