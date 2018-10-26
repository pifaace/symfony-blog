<?php

namespace App\Services\User\Manager;

use App\Entity\User;
use App\Events;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        UserRepository $repository,
        AuthorizationCheckerInterface $checker,
        UserPasswordEncoderInterface $encoder,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->repository = $repository;
        $this->checker = $checker;
        $this->encoder = $encoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(User $user): void
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        $this->repository->save($user);
    }

    public function resetPassword(User $user): void
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        $event = new GenericEvent($user);
        $this->eventDispatcher->dispatch(Events::TOKEN_RESET, $event);
        $this->repository->saveNewPassword();
    }

    public function isLogin(): bool
    {
        return $this->checker->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function isTokenExpired(User $user): bool
    {
        return $user->getTokenExpirationDate() < new \DateTime();
    }
}
