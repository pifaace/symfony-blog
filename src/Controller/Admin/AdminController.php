<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard")
     */
    public function dashBoardAction()
    {
        return $this->render('backoffice/dashboard/dashboard.html.twig');
    }

    /**
     * @Route("/admin/articles", name="admin-articles")
     */
    public function listArticleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('App:Article')->getArticlesWithComment();

        return $this->render('backoffice/article/list.html.twig', array(
            'articles' => $articles
        ));
    }
}
