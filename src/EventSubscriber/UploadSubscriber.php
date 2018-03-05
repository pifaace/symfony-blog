<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Image;
use App\Services\Uploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadSubscriber implements EventSubscriber
{
    private $uploadFile;

    public function __construct(Uploader $uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postPersist',
            'postRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->preUploadFile($entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Image && null != $entity->getFile()) {
            $this->uploadFile($entity->getFile(), $entity->getAlt());
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Article && null != $entity->getImage()) {
            $this->removeFile($entity->getImage()->getAlt());
        } elseif ($entity instanceof Image) {
            $this->removeFile($entity->getAlt());
        }
    }

    public function preUploadFile($entity)
    {
        if ($entity instanceof Image && null != $entity->getFile()) {
            $alt = $this->uploadFile->preUploadImage($entity->getFile());
            $entity->setAlt($alt);
        }
    }

    public function uploadFile($file, $imageName)
    {
        if ($file instanceof UploadedFile) {
            $this->uploadFile->uploaderImage($file, $imageName);
        }
    }

    public function removeFile($imageName)
    {
        $this->uploadFile->removeImage($imageName);
    }
}
