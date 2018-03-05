<?php

namespace App\EventSubscriber;

use App\Services\UserActionLogger;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;

class UserActionSubscriber implements EventSubscriber
{
    /**
     * @var UserActionLogger
     */
    private $logger;
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(UserActionLogger $logger, RequestStack $request)
    {
        $this->logger = $logger;
        $this->request = $request;
    }

    public function getSubscribedEvents(): array
    {
        return [
            'postPersist',
            'postUpdate',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logger->userAction(get_class($args->getObject()), $this->request, 'created');
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logger->userAction(get_class($args->getObject()), $this->request, 'updated');
    }
}
