<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\PasswordResetNewType;
use App\Form\PasswordResetRequestType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Services\FlashMessage;
use App\Services\ResetPassword;
use App\Services\User\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * @var FlashMessage
     */
    private $flashMessage;

    /**
     * @var UserManager
     */
    private $manager;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        AuthorizationCheckerInterface $checker,
        UserManager $manager,
        FlashMessage $flashMessage
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->checker = $checker;
        $this->flashMessage = $flashMessage;
        $this->manager = $manager;
    }

    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(): Response
    {
        if ($this->manager->isLogin()) {
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
     * @Route("/login/github", name="login_github", methods={"GET"})
     */
    public function loginFromGithub(): RedirectResponse
    {
        return new RedirectResponse(
            'https://github.com/login/oauth/authorize?scope=user:email&client_id='.getenv('github_client_id')
        );
    }

    /**
     * @Route("/login/github/callback", name="login_github_callback", methods={"GET"})
     */
    public function loginFromGithubCallback(): Response
    {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/registration", name="registration", methods={"GET", "POST"})
     */
    public function registration(Request $request): Response
    {
        if ($this->manager->isLogin()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->create($user);
            $this->flashMessage->createMessage(
                $request,
                FlashMessage::INFO_MESSAGE,
                'Compte créé avec succès. Vous pouvez maintenant vous connecter'
            );

            return $this->redirectToRoute('login');
        }

        return $this->render('blog/security/registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to send a password reset request.
     *
     * @Route("/password_reset/request", name="password_reset_request", methods={"GET", "POST"})
     */
    public function passwordResetRequest(
        Request $request,
        UserRepository $userRepository,
        ResetPassword $resetPassword
    ): Response {
        $form = $this->createForm(PasswordResetRequestType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy([
                'email' => $form['email']->getData(),
            ]);
            if ($user) {
                $resetPassword->reset($user);
                $this->flashMessage->createMessage($request, FlashMessage::INFO_MESSAGE, 'Un mail de réinitialisation a été envoyé à cette adresse mail');

                return $this->redirectToRoute('login');
            }

            $form->addError(new FormError("L'email renseigné n'est lié à aucun compte"));
        }

        return $this->render('blog/security/password/password_reset_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to create the new password.
     *
     * @Route("/password_reset/new", name="password_reset_new", methods={"GET", "POST"})
     */
    public function passwordResetNew(Request $request, UserRepository $userRepository): Response
    {
        $token = $request->query->get('resetPasswordToken');
        $user = $userRepository->getByValidToken($token);

        if (null === $token || empty($token) || null === $user) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(PasswordResetNewType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->manager->isTokenExpired($user)) {
                $this->manager->resetPassword($user);
                $this->flashMessage->createMessage($request, FlashMessage::INFO_MESSAGE, 'Le mot de passe a été réinitialisé avec succès !');

                return $this->redirectToRoute('login');
            }
            $this->flashMessage->createMessage($request, FlashMessage::ERROR_MESSAGE, 'Le token est expiré. Veuillez effectuer une nouvelle demande.');
        }

        return $this->render('blog/security/password/password_reset_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
