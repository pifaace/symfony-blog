<?php
/**
 * Created by PhpStorm.
 * User: joassymaxime
 * Date: 28/08/2017
 * Time: 18:51
 */

namespace AppBundle\Services;


use AppBundle\Entity\Badge;
use AppBundle\Entity\UnlockBadge;
use AppBundle\Entity\User;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var \Swift_Message
     */
    private $mailer;

    public function __construct(EngineInterface $template, \Swift_Mailer $mailer)
    {
        $this->template = $template;
        $this->mailer = $mailer;
    }

    public function unlockedBadgeEmail(Badge $badge, User $user)
    {
        $message = (new \Swift_Message('Badge débloqué !'))
            ->setFrom('platform@exemple.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render('mail/unlockBadge.html.twig', array(
                    'badge' => $badge
                )),
                'text/html'
            );
        return $this->mailer->send($message);
    }
}
