<?php

namespace App\Services\UserNotification\Factory;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserNotification;

class UserNotificationFactory
{
    public function create(Notification $notification, User $user): UserNotification
    {
        $userNotification = new UserNotification();
        $userNotification->setUser($user);
        $notification->addUserNotification($userNotification);

        return $userNotification;
    }
}
