<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/{id}/update/notification-status", name="notification-status-update", methods={"POST"})
     */
    public function __invoke(
        User $user,
        UserNotificationRepository $userNotificationRepository,
        EntityManagerInterface $em,
        Request $request
    ) {
        if ($request->isXmlHttpRequest()) {
            $unreadNotifications = $userNotificationRepository->findBy(['user' => $user, 'isRead' => false]);

            if (!empty($unreadNotifications)) {
                foreach ($unreadNotifications as $userNotification) {
                    $userNotification->setIsRead(true);
                }

                $em->flush();

                return new Response('', 204);
            }
        }

        return new Response('Invalid request');
    }
}
