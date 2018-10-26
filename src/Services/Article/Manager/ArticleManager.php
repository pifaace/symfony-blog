<?php

namespace App\Services\Article\Manager;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleManager
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        Uploader $uploader,
        ArticleRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->uploader = $uploader;
        $this->repository = $repository;
        $this->em = $em;
    }

    public function create(Article $article): void
    {
        $article->setAuthor($this->tokenStorage->getToken()->getUser());

        if (null !== $article->getImage()) {
            if ($this->uploader->hasNewImage($article->getImage())) {
                $this->uploadImage($article);
            }
        }

        $this->repository->saveNewArticle($article);
    }

    public function edit(Article $article)
    {
        $image = $article->getImage();

        if (null !== $image) {
            if ($this->uploader->hasNewImage($image)) {
                if ($this->uploader->hasActiveImage($image)) {
                    $this->uploader->removeImage($image->getAlt());
                }
                $this->uploadImage($article);
            } else {
                if ($this->uploader->hasActiveImage($image) && $this->uploader->isDeleteImageChecked($image)) {
                    $this->uploader->removeImage($image->getAlt());
                    $this->em->remove($image);
                    $article->setImage(null);
                }
            }
        }

        $this->repository->saveExistingArticle();
    }

    public function remove(Article $article): void
    {
        $this->repository->remove($article);
    }

    private function uploadImage(Article $article): void
    {
        $alt = $this->uploader->generateAlt($article->getImage()->getFile());
        $article->getImage()->setAlt($alt);
        $this->uploader->uploadImage($article->getImage()->getFile(), $alt);
    }
}
