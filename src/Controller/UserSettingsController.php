<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserSettingsController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function show(): Response
    {
        return $this->render('blog/user/profile/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
