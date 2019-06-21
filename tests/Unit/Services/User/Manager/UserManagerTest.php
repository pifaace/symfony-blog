<?php

namespace App\Tests\Unit\Services\User\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Mailer;
use App\Services\User\Manager\UserManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class UserManagerTest extends TestCase
{
    public function testCreateUser()
    {
        list(
            $userManager,
            $userRepository,
            ,
            ,
            ,
            ,
            ,
            ,
            $encoder) = $this->createUserManager();

        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class))
        ;
        $encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('password-encoded')
        ;

        $user = new User();

        $userManager->create($user);
        $this->assertEquals('password-encoded', $user->getPassword());
    }

    public function testResetPassword()
    {
        list(
            $userManager,
            $userRepository,
            ,
            $eventDispatcher,
            ,
            ,
            ,
            ,
            $encoder) = $this->createUserManager();

        $userRepository
            ->expects($this->once())
            ->method('saveNewPassword')
        ;
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
        ;
        $encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('new-password')
        ;

        $user = new User();

        $userManager->resetPassword($user);
        $this->assertEquals('new-password', $user->getPassword());
    }

    public function testIsLogin()
    {
        list(
            $userManager,
            ,
            $authorizationChecker) = $this->createUserManager();

        $authorizationChecker
            ->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;

        $this->assertTrue($userManager->isLogin());
    }

    public function testTokenIsNotExpired()
    {
        list(
            $userManager) = $this->createUserManager();

        $user = new User();
        $user->setTokenExpirationDate();
        $expired = $userManager->isTokenExpired($user);
        $this->assertFalse($expired);
    }

    /**
     * @return UserManager[]|\PHPUnit_Framework_MockObject_MockObject[]
     */
    public function createUserManager()
    {
        $userRepository = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $authorizationChecker = $this
            ->getMockBuilder(AuthorizationCheckerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $eventDispatcher = $this
            ->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mailer = $this
            ->getMockBuilder(Mailer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $translator = $this
            ->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $templating = $this
            ->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $encoder = $this
            ->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $eventDispatcher,
            $mailer,
            $translator,
            $templating,
            $requestStack,
            $encoder
        );

        return [
            $userManager,
            $userRepository,
            $authorizationChecker,
            $eventDispatcher,
            $mailer,
            $translator,
            $templating,
            $requestStack,
            $encoder,
        ];
    }
}
