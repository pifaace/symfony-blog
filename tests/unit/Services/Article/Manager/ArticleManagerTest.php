<?php

namespace App\Test\Services\Article\Manager;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Services\Article\Manager\ArticleManager;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ArticleManagerTest extends TestCase
{
    public function testCreateArticleWithNoImage()
    {
        $user = new User();
        $token = $this->getTokenMock();
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
        ;
        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->never())
            ->method('hasNewImage')
            ->willReturn(false)
        ;
        $uploader
            ->expects($this->never())
            ->method('generateAlt')
        ;
        $uploader
            ->expects($this->never())
            ->method('uploadImage')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveNewArticle')
        ;
        $em = $this->getEntityManagerMock();

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $article = new Article();
        $articleManager->create($article);

        $this->assertNull($article->getImage());
        $this->assertEquals($user, $article->getAuthor());
    }

    public function testCreateArticleWithImage()
    {
        $user = new User();
        $token = $this->getTokenMock();
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
        ;
        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->once())
            ->method('hasNewImage')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->once())
            ->method('generateAlt')
        ;
        $uploader
            ->expects($this->once())
            ->method('uploadImage')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveNewArticle')
        ;
        $em = $this->getEntityManagerMock();
        $uploadedFile = $this->getUploadedFileMock();
        $image = new Image();
        $image->setFile($uploadedFile);

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $article = new Article();
        $article->setImage($image);
        $articleManager->create($article);

        $this->assertNotNull($article->getImage());
        $this->assertEquals($user, $article->getAuthor());
    }

    public function testEditArticleWithNoImage()
    {
        $article = new Article();

        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->never())
            ->method('hasNewImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('hasActiveImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('removeImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('isDeleteImageChecked')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveExistingArticle')
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $em = $this->getEntityManagerMock();

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $articleManager->edit($article);
        $this->assertNull($article->getImage());
    }

    public function testEditArticleWithImageWhichWillBeReplaceByAnotherOne()
    {
        $article = new Article();

        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->once())
            ->method('hasNewImage')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->once())
            ->method('hasActiveImage')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->once())
            ->method('removeImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('isDeleteImageChecked')
        ;
        $uploader
            ->expects($this->once())
            ->method('uploadImage')
        ;
        $uploader
            ->expects($this->once())
            ->method('generateAlt')
            ->willReturn('newAlt')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveExistingArticle')
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $em = $this->getEntityManagerMock();

        $uploadedFile = $this->getUploadedFileMock();

        $image = new Image();
        $image->setAlt('an alt');
        $image->setFile($uploadedFile);
        $article->setImage($image);

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $articleManager->edit($article);
        $this->assertEquals('newAlt', $article->getImage()->getAlt());
    }

    public function testEditArticleWithNewImageAndNoCurrent()
    {
        $article = new Article();

        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->once())
            ->method('hasNewImage')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->once())
            ->method('hasActiveImage')
            ->willReturn(false)
        ;
        $uploader
            ->expects($this->never())
            ->method('removeImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('isDeleteImageChecked')
        ;
        $uploader
            ->expects($this->once())
            ->method('uploadImage')
        ;
        $uploader
            ->expects($this->once())
            ->method('generateAlt')
            ->willReturn('newAlt')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveExistingArticle')
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $em = $this->getEntityManagerMock();

        $uploadedFile = $this->getUploadedFileMock();

        $image = new Image();
        $image->setFile($uploadedFile);
        $article->setImage($image);

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $articleManager->edit($article);
        $this->assertEquals('newAlt', $article->getImage()->getAlt());
    }

    public function testEditArticleWithSimpleDeletedImage()
    {
        $article = new Article();

        $uploader = $this->getUploaderMock();
        $uploader
            ->expects($this->once())
            ->method('hasNewImage')
            ->willReturn(false)
        ;
        $uploader
            ->expects($this->once())
            ->method('hasActiveImage')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->once())
            ->method('removeImage')
        ;
        $uploader
            ->expects($this->once())
            ->method('isDeleteImageChecked')
            ->willReturn(true)
        ;
        $uploader
            ->expects($this->never())
            ->method('uploadImage')
        ;
        $uploader
            ->expects($this->never())
            ->method('generateAlt')
        ;
        $repository = $this->getArticleRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('saveExistingArticle')
        ;
        $tokenStorage = $this->getTokenStorageMock();
        $em = $this->getEntityManagerMock();
        $em
            ->expects($this->once())
            ->method('remove')
        ;

        $image = new Image();
        $image->setAlt('an alt');
        $article->setImage($image);

        $articleManager = new ArticleManager(
            $tokenStorage,
            $uploader,
            $repository,
            $em
        );

        $articleManager->edit($article);
        $this->assertNull($article->getImage());
    }

    private function getTokenStorageMock()
    {
        return $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getUploaderMock()
    {
        return $this
            ->getMockBuilder(Uploader::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getArticleRepositoryMock()
    {
        return $this
            ->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getEntityManagerMock()
    {
        return $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getTokenMock()
    {
        return $this
            ->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getUploadedFileMock()
    {
        return $this
            ->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock()
    ;
    }
}
