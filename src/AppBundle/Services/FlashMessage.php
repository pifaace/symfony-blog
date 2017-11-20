<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Request;

class FlashMessage
{
    public function castMessage(Request $request, int $id = null)
    {
        if (!$request instanceof Request) {
            return false;
        }

        if (null == $id) {
            $this->createArticleMessage($request);
        } else {
            $this->updateArticleMessage($request);
        }

        return true;
    }

    private function createArticleMessage(Request $request)
    {
        $request->getSession()->getFlashBag()->add('notice', "L'article a été créé avec succès");
    }

    private function updateArticleMessage(Request $request)
    {
        $request->getSession()->getFlashBag()->add('notice', "L'article a été mise à jour avec succès");
    }
}
