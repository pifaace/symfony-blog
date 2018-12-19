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
use Symfony\Component\Translation\TranslatorInterface;

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

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        UserRepository $repository,
        AuthorizationCheckerInterface $checker,
        UserPasswordEncoderInterface $encoder,
        EventDispatcherInterface $eventDispatcher,
        Mailer $mailer,
        TranslatorInterface $trans,
        \Twig_Environment $templating,
        RequestStack $requestStack
    ) {
        $this->repository = $repository;
        $this->checker = $checker;
        $this->encoder = $encoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
        $this->trans = $trans;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
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
}
