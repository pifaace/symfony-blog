<?php

namespace App\Tests\Unit\Form\DataTransformer;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
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

        $transformer = $this->getMockedTransformer();
        $tagsTransformed = $transformer->transform($tagsArray);

        $this->assertEquals('Symfony, Test, Unit', $tagsTransformed);
    }

    private function getMockedTransformer()
    {
        $entityManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new TagsTransformer($entityManager);
    }

    private function createTag($name)
    {
        $tag = new Tag();
        $tag->setName($name);

        return $tag;
    }

}
