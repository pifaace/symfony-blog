<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use App\Services\User\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    /**
     * @var Faker\Generator
     */
    private $faker;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->faker = Faker\Factory::create();
        $this->userManager = $userManager;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers();
        $this->loadTags($manager);
        $this->loadArticles($manager);
    }

    public function loadUsers(): void
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('password');
        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setRole(['ROLE_ADMIN']);

        /**
         * UserManager is using to encode the password easily
         */
        $this->userManager->create($userAdmin);

        $this->addReference('admin-user', $userAdmin);
    }

    public function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagsData() as $index => $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }
        $manager->flush();
    }

    public function loadArticles(ObjectManager $manager): void
    {
        foreach ($this->getArticleData() as [$title, $content, $author, $tags]) {
            $article = new Article();
            $article->setTitle($title);
            $article->setContent($content);
            $article->setCreateAt();
            $article->setAuthor($author);
            $article->addTag(...$tags);

            foreach (range(1, 10) as $i) {
                $comment = new Comment();
                $comment->setUser($this->getReference('admin-user'));
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

        foreach (range(1, 10) as $item => $value) {
            $post[] = [
                $this->getTitle(),
                $this->getContent(),
                $this->getReference('admin-user'),
                $this->getRandomTags(),
            ];
        }

        return $post;
    }

    private function getTitle(): string
    {
        return $this->faker->text($maxNbChars = 20);
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
            'Mideoed',
        ];
    }

    private function getRandomTags(): array
    {
        $tags = $this->getTagsData();
        shuffle($tags);

        $selectedTags = array_slice($tags, 0, rand(2, 6));

        return array_map(function ($tagName) {
            return $this->getReference('tag-'.$tagName);
        }, $selectedTags);
    }
}
