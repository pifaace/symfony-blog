<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Services\MercureCookieGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetMercureCookieSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var MercureCookieGenerator
     */
    private $cookieGenerator;

    public function __construct(TokenStorageInterface $tokenStorage, MercureCookieGenerator $cookieGenerator)
    {
        $this->cookieGenerator = $cookieGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.response' => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (null === $this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() instanceof User) {
            return;
        }

        $cookie = $this->cookieGenerator->generate();

        $event->getResponse()->headers->set(
            'set-cookie',
            $cookie
        );
    }
}
