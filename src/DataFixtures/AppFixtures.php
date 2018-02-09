<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
        $this->faker = Faker\Factory::create();
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

        foreach (range(1, 5) as $i) {
            $comment = new Comment();
            $comment->setUsername($this->faker->name);
            $comment->setEmail($this->faker->email);
            $comment->setContent($this->faker->text());
            $comment->setCreateAt();

            $article->addComment($comment);
        }

        $manager->persist($article);
        $manager->flush();

        $this->addReference('article', $article);
    }

    public function getContent()
    {
        return $this->faker->text($maxNbChars = 600);
    }
}
