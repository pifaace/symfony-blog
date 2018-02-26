<?php

namespace App\Tests\Unit\Form\DataTransformer;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function testDuplicateTagsName()
    {
        $tags = $this->getMockedTransformer()->reverseTransform('Hello,Hello,Symfony,Symfony,Test,Symfony');
        $this->assertCount(3, $tags);
    }

    public function testAlreadyDefinedTags()
    {
        $tagArray = [
            $this->createTag('Symfony'),
            $this->createTag('Unit'),
        ];

        $tags = $this->getMockedTransformer($tagArray)->reverseTransform('Symfony,Unit,Feature,Docker');
        $this->assertCount(4, $tags);
        $this->assertSame($tagArray[0], $tags[0]);
        $this->assertSame($tagArray[1], $tags[1]);
    }

    public function testRemoveEmptyTags()
    {
        $tags = $this->getMockedTransformer()->reverseTransform('Unit, , , ,,Symfony');
        $this->assertCount(2, $tags);
        $this->assertEquals('Symfony', $tags[1]);
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
