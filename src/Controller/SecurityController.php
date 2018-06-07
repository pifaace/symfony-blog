<?php

namespace App\Controller;

use App\Entity\User;
use App\Events;
use App\Form\LoginType;
use App\Form\PasswordResetNewType;
use App\Form\PasswordResetRequestType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Services\FlashMessage;
use App\Services\ResetPassword;
use EightPoints\Bundle\GuzzleBundle\Events\GuzzleEventListenerInterface;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\GuardAuthenticationFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @Route("/login/github", name="login_github")
     * @Method("GET")
     *
     * @return RedirectResponse
     */
    public function loginFromGithub()
    {
        return new RedirectResponse('https://github.com/login/oauth/authorize?scope=user:email&client_id=' . getenv('github_client_id'));
    }

    /**
     * @Route("/login/github/callback", name="login_github_callback")
     * @Method("GET")
     *
     */
    public function loginFromGithubCallback()
    {
        return $this->redirectToRoute('homepage');
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
     *
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

            $flashMessage->createMessage($request, $flashMessage::INFO_MESSAGE, 'Compte créé avec succès. Vous pouvez maintenant vous connecter');

            return $this->redirectToRoute('login');
        }

        return $this->render('blog/security/registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to send a password reset request.
     *
     * @Route("/password_reset/request", name="password_reset_request")
     * @Method({"GET", "POST"})
     *
     * @param Request        $request
     * @param UserRepository $userRepository
     * @param ResetPassword  $resetPassword
     * @param FlashMessage   $flashMessage
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordResetRequest(Request $request, UserRepository $userRepository, ResetPassword $resetPassword, FlashMessage $flashMessage): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy([
                'email' => $form['email']->getData(),
            ]);
            if ($user) {
                $resetPassword->reset($user);
                $flashMessage->createMessage($request, $flashMessage::INFO_MESSAGE, 'Un mail de réinitialisation a été envoyé à cette adresse mail');

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
     * @Route("/password_reset/new", name="password_reset_new")
     * @Method({"GET", "POST"})
     *
     * @param Request                      $request
     * @param UserRepository               $userRepository
     * @param FlashMessage                 $flashMessage
     * @param UserPasswordEncoderInterface $encoder
     * @param EventDispatcherInterface     $eventDispatcher
     *
     * @return Response
     */
    public function passwordResetNew(Request $request, UserRepository $userRepository, FlashMessage $flashMessage, UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher): Response
    {
        $token = $request->query->get('resetPasswordToken');
        $user = $userRepository->getByValidToken($token);

        if (is_null($token) || empty($token) || is_null($user)) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(PasswordResetNewType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isTokenNotExpired($user)) {
                $em = $this->getDoctrine()->getManager();
                $user->setPassword($encoder->encodePassword($user, $user->getPlainPassword()));

                $event = new GenericEvent($user);
                $eventDispatcher->dispatch(Events::TOKEN_RESET, $event);
                $em->flush();
                $flashMessage->createMessage($request, $flashMessage::INFO_MESSAGE, 'Le mot de passe a été réinitialisé avec succès !');

                return $this->redirectToRoute('login');
            }
            $flashMessage->createMessage($request, $flashMessage::ERROR_MESSAGE, 'Le token est expiré. Veuillez effectuer une nouvelle demande.');
        }

        return $this->render('blog/security/password/password_reset_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function isLogin()
    {
        return $this->checker->isGranted('IS_AUTHENTICATED_FULLY');
    }

    private function isTokenNotExpired(User $user)
    {
        return $user->getTokenExpirationDate() > new \DateTime();
    }
}
