<?php

namespace App\Tests\Services;

use App\Entity\Image;
use App\Services\Uploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderTest extends TestCase
{
    /**
     * @var Uploader
     */
    private $uploader;

    public function setUp()
    {
        $this->uploader = new Uploader('%kernel.project_dir%/public/uploads/articles/coverages/');
    }

    public function testHasNotNewImage()
    {
        $image = $this->getImage();
        $this->assertFalse($this->uploader->hasNewImage($image));
    }

    public function testHasNewImage()
    {
        $image = $this->getImage();
        $image->setFile($this->mockedFile('test.png'));
        $this->assertTrue($this->uploader->hasNewImage($image));
    }

    public function testHasNoActiveImage()
    {
        $image = $this->getImage();
        $this->assertFalse($this->uploader->hasActiveImage($image));
    }

    public function testHasActiveImage()
    {
        $image = $this
            ->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();
        $image
            ->method('getId')
            ->willReturn(384);

        $this->assertTrue($this->uploader->hasActiveImage($image));
    }

    public function testDeletedImageIsChecked()
    {
        $image = $this->getImage();
        $image->setDeletedImage(true);

        $this->assertTrue($this->uploader->isDeleteImageChecked($image));
    }

    public function testDeletedImageIsNotChecked()
    {
        $image = $this->getImage();
        $image->setDeletedImage(false);

        $this->assertFalse($this->uploader->isDeleteImageChecked($image));
    }

    public function testUploadImage()
    {
        $uploadedFile = $this
            ->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploadedFile
            ->expects($this->once())
            ->method('move');

        $this->uploader->uploadImage($uploadedFile, 'sweet_name');
    }

    private function mockedFile(string $fileName): UploadedFile
    {
        return new UploadedFile(__FILE__, $fileName);
    }

    private function getImage(): Image
    {
        return new Image();
    }
}
