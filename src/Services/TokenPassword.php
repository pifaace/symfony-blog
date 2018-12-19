<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * This service should be called to set a token for a target user.
 * This token is necessary to request a password change.
 */
class TokenPassword
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $token;

    public function __construct(
        EntityManagerInterface $em,
        TokenGeneratorInterface $generator
    ) {
        $this->em = $em;
        $this->generator = $generator;
    }

    /**
     * Generate and add a temporary token to the target user to allow a reset password.
     */
    public function addToken(User $user): void
    {
        $this->token = $this->generateToken();
        $user->setResetPasswordToken($this->token);
        $user->setTokenExpirationDate();
        $this->em->flush();
    }

    private function generateToken(): string
    {
        return $this->generator->generateToken();
    }
}
