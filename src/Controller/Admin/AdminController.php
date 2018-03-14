<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard")
     * @Method("GET")
     */
    public function dashBoard()
    {
        return $this->render('backoffice/dashboard/dashboard.html.twig');
    }

    /**
     * @Route("/admin/articles", name="admin-articles")
     * @Method("GET")
     */
    public function listArticle()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('App:Article')->getArticlesWithComment();

        return $this->render('backoffice/article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}
