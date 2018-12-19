<?php

namespace App\Tests\Services;

use App\Entity\User;
use App\Services\TokenPassword;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
        $trans = $this->getTransMock();
        $masterRequest = $this->getRequestMock();
        $masterRequest
            ->expects($this->once())
            ->method('getLocale')
            ->willReturn('en')
        ;
        $request = $this->getRequestStackMock();
        $request
            ->expects($this->once())
            ->method('getMasterRequest')
            ->willReturn($masterRequest)
        ;

        $user = new User();

        $resetPassword = new TokenPassword($em, $swiftMailer, $twig, $tokenGenerator, $trans, $request);
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

    private function getTransMock()
    {
        return $this
            ->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRequestStackMock()
    {
        return $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRequestMock()
    {
        return $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
