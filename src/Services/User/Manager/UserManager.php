<?php

namespace App\Services\User\Manager;

use App\Entity\User;
use App\Events;
use App\Repository\UserRepository;
use App\Services\Mailer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        UserRepository $repository,
        AuthorizationCheckerInterface $checker,
        EventDispatcherInterface $eventDispatcher,
        Mailer $mailer,
        TranslatorInterface $trans,
        Environment $templating,
        RequestStack $requestStack,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->repository = $repository;
        $this->checker = $checker;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
        $this->trans = $trans;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
        $this->encoder = $encoder;
    }

    public function create(User $user): void
    {
        $user->setPassword($this->encodePassword($user));
        $this->repository->save($user);
    }

    public function resetPassword(User $user): void
    {
        $genericEvent = new GenericEvent($user);
        $this->eventDispatcher->dispatch(Events::TOKEN_RESET, $genericEvent);

        $user->setPassword($this->encodePassword($user));
        $this->repository->saveNewPassword();
    }

    public function sendPasswordRequestEmail(User $user)
    {
        $this->mailer->buildAndSendMail(
            $this->trans->trans('reset_password.title', [], 'emails'),
            $user->getEmail(),
            $this
                ->templating
                ->render('email/password_request/_password_reset_email_'.
                    $this->requestStack->getMasterRequest()->getLocale().'.html.twig', [
                'username' => $user->getUsername(),
                'token' => $user->getResetPasswordToken(),
                ])
            );
    }

    public function isLogin(): bool
    {
        return $this->checker->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function isTokenExpired(User $user): bool
    {
        return $user->getTokenExpirationDate() < new \DateTime();
    }

    private function encodePassword(User $user): string
    {
        return $this->encoder->encodePassword($user, $user->getPlainPassword());
    }
}
