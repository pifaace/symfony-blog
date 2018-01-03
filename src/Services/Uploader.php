<?php

namespace App\Services;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function hasNewImage(Image $image)
    {
        if (!$image->getFile() == null)
            return true;

        return false;
    }

    public function noImage(Image $image)
    {
        if ($image->getId() || $image->getFile() != null)
            return false;

        return true;
    }

    public function isDeleteImageChecked($formData)
    {
        if($formData->getImage()->isDeletedImage())
            return true;

        return false;
    }

    public function preUploadImage(UploadedFile $file)
    {
        $alt = md5(uniqid()) . '.' . $file->guessExtension();
        return $alt;
    }

    public function uploaderImage(UploadedFile $file, $imageName)
    {
        $file->move($this->getTargetDir(), $imageName);
    }

    public function removeImage($imageName)
    {
        $fs = new Filesystem();

        if ($fs->exists($this->getTargetDir() . $imageName)) {
            unlink($this->getTargetDir() . $imageName);
        }
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}

