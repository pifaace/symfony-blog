<?php

namespace App\Services\Notification\Factory;

use App\Entity\Article;
use App\Entity\Notification;
use App\Repository\NotificationTypeRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationFactory
{
    /**
     * @var NotificationTypeRepository
     */
    private $notificationTypeRepository;
    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        NotificationTypeRepository $notificationTypeRepository,
        TokenStorageInterface $storage,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->storage = $storage;
        $this->urlGenerator = $urlGenerator;
    }

    public function create(Article $article, string $notificationTypeName): Notification
    {
        $notificationType = $this->notificationTypeRepository->findOneBy(['name' => $notificationTypeName]);
        $url = $this->urlGenerator->generate('article_show', ['slug' => $article->getSlug()]);

        $notification = (new Notification())
                ->setNotificationType($notificationType)
                ->setCreatedBy($this->storage->getToken()->getUser())
                ->setTargetLink($url);

        return $notification;
    }
}
