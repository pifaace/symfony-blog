<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserActionLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string       $entityName
     * @param RequestStack $request
     * @param string       $status
     */
    public function userAction(string $entityName, RequestStack $request, string $status)
    {
        $this->logger->info('User with IP : '.$request->getMasterRequest()->getClientIp().' '.$status.' : '.$entityName);
    }
}
