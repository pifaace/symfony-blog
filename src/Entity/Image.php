<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image.
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpg"},
     *     mimeTypesMessage="Le fichier n'est pas une image valide"
     * )
     */
    private $file;

    /**
     * @var bool
     */
    private $deletedImage;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @return bool
     */
    public function isDeletedImage(): ?bool
    {
        return $this->deletedImage;
    }

    /**
     * @param bool $deletedImage
     */
    public function setDeletedImage(bool $deletedImage)
    {
        $this->deletedImage = $deletedImage;
    }
}
