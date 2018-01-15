<?php

namespace App\Tests\Unit\Services;

use App\Entity\Image;
use App\Services\Uploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderTest extends TestCase
{
    private $image;

    private $uploader;

    public function setUp()
    {
        $this->image = new Image();
        $this->uploader = new Uploader('%kernel.project_dir%/public/uploads/articles/coverages/');
    }

    public function testHasNotNewImage()
    {
        $this->assertFalse($this->uploader->hasNewImage($this->image), "No new image");
    }

    public function testHasNewImage()
    {
        $this->image->setFile($this->mockedFile('test.png'));
        $this->assertTrue($this->uploader->hasNewImage($this->image), "new image");
    }

    /**
     * @param $fileName
     * @return UploadedFile
     */
    private function mockedFile($fileName)
    {
        return new UploadedFile(__FILE__, $fileName, null, null, null, true);
    }
}
