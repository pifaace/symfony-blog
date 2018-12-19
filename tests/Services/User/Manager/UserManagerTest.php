<?php

namespace App\Test\Services\User\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Mailer;
use App\Services\User\Manager\UserManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserManagerTest extends TestCase
{
    public function testCreateUser()
    {
        list(
            $userManager,
            $userRepository,
            ,
            $passwordEncoder) = $this->createUserManager();

        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class))
        ;
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded-password')
        ;

        $user = new User();
        $user->setPlainPassword('password');

        $userManager->create($user);
    }

    public function testResetPassword()
    {
        list(
            $userManager,
            $userRepository,
            ,
            $passwordEncoder,
            $eventDispatcher) = $this->createUserManager();

        $userRepository
            ->expects($this->once())
            ->method('saveNewPassword')
        ;
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded-password')
        ;
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
        ;

        $user = new User();
        $user->setPlainPassword('password');

        $userManager->resetPassword($user);

        $this->assertEquals('encoded-password', $user->getPassword());
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
        $passwordEncoder = $this
            ->getMockBuilder(UserPasswordEncoderInterface::class)
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
            ->getMockBuilder(\Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher,
            $mailer,
            $translator,
            $templating,
            $requestStack
        );

        return [
            $userManager,
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher,
            $mailer,
            $translator,
            $templating,
            $requestStack,
        ];
    }
}
