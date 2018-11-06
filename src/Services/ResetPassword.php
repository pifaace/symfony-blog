<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPassword
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var TokenGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $token;

    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        \Twig_Environment $templating,
        TokenGeneratorInterface $generator,
        TranslatorInterface $trans,
        RequestStack $request
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->generator = $generator;
        $this->trans = $trans;
        $this->request = $request;
    }

    public function reset(User $user): void
    {
        $this->addToken($user);
        $this->sendResetPasswordEmail($user, $this->token);
    }

    /**
     * Generate and add a temporary token to the target user.
     */
    private function addToken(User $user): void
    {
        $this->token = $this->generateToken();
        $user->setResetPasswordToken($this->token);
        $user->setTokenExpirationDate();
        $this->em->flush();
    }

    private function sendResetPasswordEmail(User $user, string $token): void
    {
        $message = (new \Swift_Message($this->trans->trans('reset_password.title', [], 'emails')))
            ->setFrom('no-remply@symfony-blog.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this
                    ->templating
                    ->render('blog/security/password/email/_password_reset_email_'.
                        $this->request->getMasterRequest()->getLocale().'.html.twig', [
                    'username' => $user->getUsername(),
                    'token' => $token,
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    private function generateToken(): string
    {
        return $this->generator->generateToken();
    }
}
