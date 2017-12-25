<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class FlashMessage
{
    public function createMessage(Request $request, string $type, string $message)
    {
        if (!$request instanceof Request) {
            return false;
        }

        $request->getSession()->getFlashBag()->add($type, $message);

        return true;
    }
}
