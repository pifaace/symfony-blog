<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserSettingsController extends Controller
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
