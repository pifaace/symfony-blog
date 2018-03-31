<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\PasswordResetType;
use App\Form\RegistrationType;
use App\Services\FlashMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    public function __construct(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $checker)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->checker = $checker;
    }

    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function login(): Response
    {
        if ($this->isLogin()) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(LoginType::class);
        $lastUserName = $this->authenticationUtils->getLastUsername();
        $error = $this->authenticationUtils->getLastAuthenticationError();

        return $this->render('blog/security/login/login.html.twig', [
            'form' => $form->createView(),
            'lastUserName' => $lastUserName,
            'error' => $error,
        ]);
    }

    /**
     * User password is encoded in EventListener/EncoderUserPassword class
     * thanks to Doctrine listener 'prePersist'.
     *
     * @Route("/registration", name="registration")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param FlashMessage $flashMessage
     * @return Response
     */
    public function registration(Request $request, FlashMessage $flashMessage): Response
    {
        if ($this->isLogin()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setRole(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $flashMessage->createMessage($request, 'info', 'Compte créé avec succès. Vous pouvez maintenant vous connecter');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('blog/security/registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password_reset", name="password_reset")
     * @Method({"GET", "POST"})
     */
    public function passwordReset()
    {
        $form = $this->createForm(PasswordResetType::class);

        return $this->render("blog/security/password/password_reset.html.twig", [
            'form' => $form->createView()
        ]);
    }

    private function isLogin()
    {
        return $this->checker->isGranted('IS_AUTHENTICATED_FULLY');
    }
}
