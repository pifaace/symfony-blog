<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * This class will delete the reset password token after that it has been used.
 */
class PasswordTokenReset
{
    public function resetToken(GenericEvent $event): void
    {
        $user = $event->getSubject();
        if (!$user instanceof User && null === $user->getResetPasswordToken()) {
            return;
        }
        $user->setResetPasswordToken(null);
    }
}
