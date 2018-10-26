<?php

namespace App\Tests\Services;

use App\Entity\User;
use App\Services\ResetPassword;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordTest extends TestCase
{
    public function testReset()
    {
        $em = $this->getEntityManagerMock();
        $em
            ->expects($this->once())
            ->method('flush')
        ;
        $swiftMailer = $this->getSwiftMailerMock();
        $swiftMailer
            ->expects($this->once())
            ->method('send')
        ;
        $twig = $this->getTwigMock();
        $tokenGenerator = $this->getTokenGeneratorMock();
        $tokenGenerator
            ->expects($this->any())
            ->method('generateToken')
            ->willReturn('token-genereted')
        ;

        $user = new User();

        $resetPassword = new ResetPassword($em, $swiftMailer, $twig, $tokenGenerator);
        $resetPassword->reset($user);

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

    private function getSwiftMailerMock()
    {
        return $this
            ->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getTwigMock()
    {
        return $this
            ->getMockBuilder(\Twig_Environment::class)
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
