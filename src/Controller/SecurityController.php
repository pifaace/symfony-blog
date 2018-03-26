<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function login(): Response
    {
        $form = $this->createForm(LoginType::class);
        $lastUserName = $this->authenticationUtils->getLastUsername();
        $error = $this->authenticationUtils->getLastAuthenticationError();

        return $this->render('blog/security/login.html.twig', [
            'form' => $form->createView(),
            'lastUserName' => $lastUserName,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/registration", name="registration")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function registration(): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render('blog/security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
