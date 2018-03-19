<?php

namespace App\Controller;

use App\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function login(): Response
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('backoffice/security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
