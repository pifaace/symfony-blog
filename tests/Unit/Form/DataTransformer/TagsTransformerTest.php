<?php

namespace App\Tests\Unit\Form\DataTransformer;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TagsTransformerTest extends TestCase
{
    public function testTransform()
    {
        $tagsArray = [
            $this->createTag('Symfony'),
            $this->createTag('Test'),
            $this->createTag('Unit'),
        ];

        $tagsTransformed = $this->getMockedTransformer()->transform($tagsArray);

        $this->assertEquals('Symfony, Test, Unit', $tagsTransformed);
    }

    public function testTrimName()
    {
        $tag = $this->getMockedTransformer()->reverseTransform('   Symfony   ');
        $this->assertEquals('Symfony', $tag[0]->getName());
    }

    private function getMockedTransformer(array $findByReturnValues = []): TagsTransformer
    {
        $tagRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tagRepository->expects($this->any())
            ->method('findBy')
            ->willReturn($findByReturnValues);


        $entityManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($tagRepository);

        return new TagsTransformer($entityManager);
    }

    private function createTag($name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);

        return $tag;
    }

}
