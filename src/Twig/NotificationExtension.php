<?php

namespace App\Twig;

use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * This extension creates a global variable of number of notification for the current user.
 */
class NotificationExtension
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(NotificationRepository $notificationRepository, TokenStorageInterface $tokenStorage)
    {
        $this->notificationRepository = $notificationRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns unread notifications to the twig global variable 'notifications'.
     *
     * @return array
     */
    public function getNotifications()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $notifications = $this->notificationRepository->getUnreadNotification($user);

        return $notifications;
    }
}
