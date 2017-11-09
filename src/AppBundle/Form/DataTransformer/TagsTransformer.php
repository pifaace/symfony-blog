<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsTransformer implements DataTransformerInterface
{

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function transform($value): string
    {
        return implode(', ', $value);
    }

    public function reverseTransform($string): array
    {
        $names = array_filter(array_unique(array_map('trim', explode(',', $string))));

        $tags = $this->em->getRepository('AppBundle:Tag')->findBy([
            'name' => $names
        ]);

        $newNames = array_diff($names, $tags);

        foreach ($newNames as $newName) {
            $tag = new Tag();
            $tag->setName($newName);

            $tags[] = $tag;
        }

        return $tags;
    }
}
