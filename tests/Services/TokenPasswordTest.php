<?php

namespace App\Tests\Services;

use App\Entity\User;
use App\Services\TokenPassword;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenPasswordTest extends TestCase
{
    public function testAddToken()
    {
        $em = $this->getEntityManagerMock();
        $em
            ->expects($this->once())
            ->method('flush')
        ;
        $tokenGenerator = $this->getTokenGeneratorMock();
        $tokenGenerator
            ->expects($this->any())
            ->method('generateToken')
            ->willReturn('token-genereted')
        ;

        $user = new User();

        $resetPassword = new TokenPassword($em, $tokenGenerator);
        $resetPassword->addToken($user);

        $this->assertEquals('token-genereted', $user->getResetPasswordToken());
        $this->assertNotNull($user->getTokenExpirationDate());
    }

    private function getEntityManagerMock()
    {
        return $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getTokenGeneratorMock()
    {
        return $this
            ->getMockBuilder(TokenGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
