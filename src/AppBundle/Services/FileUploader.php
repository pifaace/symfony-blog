<?php

namespace AppBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
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
        unlink($this->getTargetDir() . $imageName);
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
