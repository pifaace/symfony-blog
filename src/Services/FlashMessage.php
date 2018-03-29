<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class FlashMessage
{
    /**
     * Service to generate a flashmessage depends on type
     *
     * @param Request $request
     * @param string  $type
     * @param string  $message
     * @return bool
     */
    public function createMessage(Request $request, string $type, string $message)
    {
        if (!$request instanceof Request) {
            return false;
        }

        $request->getSession()->getFlashBag()->add($type, $message);

        return true;
    }
}
