<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/user")
 */
class UserSettingsController extends Controller
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function show()
    {
        return $this->render('blog/user/profile/show.html.twig');
    }
}
