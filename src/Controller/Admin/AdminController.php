<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard", methods={"GET"})
     */
    public function dashBoard(): Response
    {
        return $this->render('backoffice/dashboard/dashboard.html.twig');
    }

    /**
     * @Route("/admin/articles", name="admin-articles", methods={"GET"})
     *
     * @param ArticleRepository $articleRepository
     *
     * @return Response
     */
    public function listArticle(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->getArticlesWithComment();

        return $this->render('backoffice/article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}
