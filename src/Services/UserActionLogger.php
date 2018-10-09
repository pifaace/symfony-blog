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

    public function userAction(string $entityName, string $status): void
    {
        $this->logger->info('User '.$status.' : '.$entityName);
    }
}
