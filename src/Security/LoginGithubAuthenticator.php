<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\FlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginGithubAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var FlashMessage
     */
    private $flashMessage;

    /**
     * @var Route
     */
    private $router;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $trans;

    public function __construct(
        Client $client,
        FlashMessage $flashMessage,
        RouterInterface $router,
        UserRepository $userRepo,
        EntityManagerInterface $em,
        TranslatorInterface $trans
    ) {
        $this->client = $client;
        $this->flashMessage = $flashMessage;
        $this->router = $router;
        $this->userRepo = $userRepo;
        $this->em = $em;
        $this->trans = $trans;
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *  A) For a form login, you might redirect to the login page
     *      return new RedirectResponse('/login');
     *  B) For an API token authentication system, you return a 401 response
     *      return new Response('Auth header required', 401);
     *
     * @param Request                 $request       The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     */
    public function supports(Request $request): bool
    {
        return 'login_github_callback' === $request->attributes->get('_route');
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array).
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      return array(
     *          'username' => $request->request->get('_username'),
     *          'password' => $request->request->get('_password'),
     *      );
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return array('api_key' => $request->headers->get('X-API-TOKEN'));
     *
     * @return mixed Any non-null value
     *
     * @throws \UnexpectedValueException If null is returned
     */
    public function getCredentials(Request $request)
    {
        $code = $request->query->get('code');
        $uri = 'https://github.com/login/oauth/access_token?client_id='.getenv('github_client_id').'&client_secret='.getenv('github_secret_id').'&code='.$code;
        $response = $this->client->post($uri);

        $jsonResponse = $response->getBody()->getContents();

        $token = json_decode($jsonResponse, true);

        if (isset($token['error'])) {
            throw new BadCredentialsException('No access_token returned by Github', 401);
        }

        return $token;
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     *
     * @throws AuthenticationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $response = $this->client->get('https://api.github.com/user?access_token='.$credentials['access_token']);
        $userDatas = json_decode($response->getBody()->getContents(), true);

        if (null === $userDatas['email']) {
            throw new AuthenticationException('Your account has no email, please fill in it', 401);
        }

        $user = $this->userRepo->getByProviderId($userDatas['id']);

        if ($user) {
            return $user;
        }

        if ($this->userRepo->findBy(['username' => $userDatas['login']])) {
            throw new AuthenticationException('Your username from Github is already used on this app');
        }

        if ($this->userRepo->findBy(['email' => $userDatas['email']])) {
            throw new AuthenticationException('Your email from Github is already used on this app');
        }

        $user = new User();
        $user->setUsername($userDatas['login']);
        $user->setEmail($userDatas['email']);
        $user->setProviderId((int) $userDatas['id']);
        $user->setRole(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param $credentials
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->flashMessage->createMessage($request, $this->flashMessage::ERROR_MESSAGE, $exception->getMessage());

        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param string $providerKey The provider (i.e. firewall) key
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        $this->flashMessage->createMessage(
            $request,
            FlashMessage::INFO_MESSAGE,
            $this->trans->trans('login.flashmessage_success')
        );

        return new RedirectResponse($this->router->generate('homepage'));
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *  D) The onAuthenticationSuccess method returns a Response object
     */
    public function supportsRememberMe(): bool
    {
        // TODO: Implement supportsRememberMe() method.
    }
}
