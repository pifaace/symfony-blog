<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class FlashMessage
{
    public const INFO_MESSAGE = 'info';
    public const ERROR_MESSAGE = 'error';

    /**
     * Service to generate a flashmessage depends on type.
     */
    public function createMessage(Request $request, string $type, string $message): bool
    {
        if (!$request instanceof Request) {
            return false;
        }

        $request->getSession()->getFlashBag()->add($type, $message);

        return true;
    }
}
