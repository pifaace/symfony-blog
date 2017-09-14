<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/admin/articles/list", name="admin-articles-list")
     */
    public function listArticleAction()
    {
        return $this->render('backoffice/article/list.html.twig');
    }
}
