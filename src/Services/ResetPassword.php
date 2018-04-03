<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function reset($user)
    {
        $this->addToken($user);
        $this->sendResetPasswordEmail($user);
    }

    /**
     * Generate and add a temporary token to the target user
     * @param $user
     */
    public function addToken($user)
    {
        $token = $this->generateToken();
        $user->setResetPasswordToken($token);
        $this->em->flush();
    }

    public function sendResetPasswordEmail($user)
    {
        $message = (new \Swift_Message('Demande reinitialisation de mot de passe'))
            ->setFrom('no-remply@symfony-blog.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('blog/security/password/email/_password_reset_email.html.twig', [
                    'username' => $user->getUsername()
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * @return string
     */
    private function generateToken()
    {
        return base64_encode(random_bytes(30));
    }
}
