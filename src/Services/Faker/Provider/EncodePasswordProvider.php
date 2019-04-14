<?php

namespace App\Services\Faker\Provider;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodePasswordProvider
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encodePassword(User $user, string $plainPassword)
    {
        return $this->encoder->encodePassword($user, $plainPassword);
    }
}
