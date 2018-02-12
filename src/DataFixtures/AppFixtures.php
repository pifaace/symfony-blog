<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
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
        $this->loadTags($manager);
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

    public function loadTags(ObjectManager $manager)
    {
        foreach ($this->getTagsData() as $index => $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-' . $name, $tag);
        }
        $manager->flush();
    }

    public function loadArticles(ObjectManager $manager)
    {
        foreach ($this->getArticleData() as [$title, $content, $author, $tags]) {
            $article = new Article();
            $article->setTitle($title);
            $article->setContent($content);
            $article->setCreateAt();
            $article->setAuthor($author);
            $article->addTag(...$tags);

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
        }
    }

    private function getArticleData(): array
    {
        $post = [];

        foreach (range(1, 1) as $item => $value) {
            $post[] = [
                'An article test for my blog',
                $this->getContent(),
                $this->getReference('admin-user'),
                $this->getRandomTags()
            ];
        }

        return $post;
    }

    private function getContent(): string
    {
        return $this->faker->text($maxNbChars = 600);
    }

    private function getTagsData(): array
    {
        return [
            'Lorem',
            'Broh',
            'Docker',
            'Ipso',
            'Odopl',
            'Blaorp',
            'Mideoed'
        ];
    }

    private function getRandomTags()
    {
        $tags = $this->getTagsData();
        shuffle($tags);

        $selectedTags = array_slice($tags, 0, rand(2, 6));

        return array_map(function ($tagName) {
            return $this->getReference('tag-' . $tagName);
        }, $selectedTags);
    }
}
