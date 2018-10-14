<?php

namespace App\Services;

use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function hasNewImage(Image $image): bool
    {
        return null !== $image->getFile() && $image->getFile() instanceof UploadedFile;
    }

    public function hasActiveImage(Image $image): bool
    {
        return null !== $image->getId();
    }

    public function isDeleteImageChecked(Image $image): bool
    {
        return $image->isDeletedImage();
    }

    public function generateAlt(UploadedFile $file): string
    {
        return md5(uniqid('', true)).'.'.$file->guessExtension();
    }

    public function uploadImage(UploadedFile $file, string $imageName): void
    {
        $file->move($this->getTargetDir(), $imageName);
    }

    public function removeImage(string $imageName): void
    {
        $fs = new Filesystem();

        if ($fs->exists($this->getTargetDir().$imageName)) {
            unlink($this->getTargetDir().$imageName);
        }
    }

    private function getTargetDir(): string
    {
        return $this->targetDir;
    }
}
