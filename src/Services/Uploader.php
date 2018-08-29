<?php

namespace App\Services;

use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function hasNewImage(Image $image): bool
    {
        if (null !== $image->getFile()) {
            return true;
        }

        return false;
    }

    public function noImage(Image $image): bool
    {
        if ($image instanceof Image) {
            if (null !== $image->getId() || null !== $image->getFile()) {
                return false;
            }
        }

        return true;
    }

    public function isDeleteImageChecked($formData): bool
    {
        if ($formData->getImage()->isDeletedImage()) {
            return true;
        }

        return false;
    }

    public function preUploadImage(UploadedFile $file): string
    {
        return md5(uniqid('', true)).'.'.$file->guessExtension();
    }

    public function uploaderImage(UploadedFile $file, $imageName): void
    {
        $file->move($this->getTargetDir(), $imageName);
    }

    public function removeImage($imageName): void
    {
        $fs = new Filesystem();

        if ($fs->exists($this->getTargetDir().$imageName)) {
            unlink($this->getTargetDir().$imageName);
        }
    }

    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
}
