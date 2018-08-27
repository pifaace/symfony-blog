<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class UserActionLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $userActionLogger)
    {
        $this->logger = $userActionLogger;
    }

    /**
     * @param string $entityName
     * @param string $status
     */
    public function userAction(string $entityName, string $status): void
    {
        $this->logger->info('User '.$status.' : '.$entityName);
    }
}
