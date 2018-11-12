<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PreferredLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $supportedLocales;

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(array $supportedLocales, string $locale)
    {
        $this->supportedLocales = $supportedLocales;
        $this->defaultLocale = $locale;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $localeNeeded = $request->getSession()->get('locale');

        if (in_array($localeNeeded, $this->supportedLocales)) {
            $request->setLocale($localeNeeded);
        } else {
            $request->setLocale($this->defaultLocale);
        }
    }
}
