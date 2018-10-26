<?php

namespace App\Test\Services\User\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\User\Manager\UserManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends TestCase
{
    public function testCreateUser()
    {
        $userRepository = $this->getUserRepositoryMock();
        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class))
        ;
        $authorizationChecker = $this->getAuthorizationCheckerMock();
        $passwordEncoder = $this->getUserPasswordEncoderMock();
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded-password')
        ;
        $eventDispatcher = $this->getEventDispatcherMock();

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher
        );

        $user = new User();
        $user->setPlainPassword('password');

        $userManager->create($user);
    }

    public function testResetPassword()
    {
        $userRepository = $this->getUserRepositoryMock();
        $userRepository
            ->expects($this->once())
            ->method('saveNewPassword')
        ;
        $authorizationChecker = $this->getAuthorizationCheckerMock();
        $passwordEncoder = $this->getUserPasswordEncoderMock();
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded-password')
        ;
        $eventDispatcher = $this->getEventDispatcherMock();
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
        ;

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher
        );

        $user = new User();
        $user->setPlainPassword('password');

        $userManager->resetPassword($user);
    }

    public function testIsLogin()
    {
        $userRepository = $this->getUserRepositoryMock();
        $authorizationChecker = $this->getAuthorizationCheckerMock();
        $authorizationChecker
            ->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;
        $passwordEncoder = $this->getUserPasswordEncoderMock();
        $eventDispatcher = $this->getEventDispatcherMock();

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher
        );
        $this->assertTrue($userManager->isLogin());
    }

    public function testTokenIsNotExpired()
    {
        $userRepository = $this->getUserRepositoryMock();
        $authorizationChecker = $this->getAuthorizationCheckerMock();
        $passwordEncoder = $this->getUserPasswordEncoderMock();
        $eventDispatcher = $this->getEventDispatcherMock();

        $userManager = new UserManager(
            $userRepository,
            $authorizationChecker,
            $passwordEncoder,
            $eventDispatcher
        );
        $user = new User();
        $user->setTokenExpirationDate();
        $expired = $userManager->isTokenExpired($user);
        $this->assertFalse($expired);
    }

    private function getUserRepositoryMock()
    {
        return $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getAuthorizationCheckerMock()
    {
        return $this
            ->getMockBuilder(AuthorizationCheckerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getUserPasswordEncoderMock()
    {
        return $this
            ->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getEventDispatcherMock()
    {
        return $this
            ->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
