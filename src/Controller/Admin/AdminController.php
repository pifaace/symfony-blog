<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard", methods={"GET"})
     *
     * @param ArticleRepository $article
     * @param CommentRepository $comment
     * @param UserRepository    $user
     *
     * @return Response
     */
    public function dashBoard(ArticleRepository $article, CommentRepository $comment, UserRepository $user): Response
    {
        return $this->render('backoffice/dashboard/dashboard.html.twig', [
            'countArticles' => $article->countArticles(),
            'countComments' => $comment->countComments(),
            'countUsers'    => $user->countUsers(),
        ]);
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
        return $this->render('backoffice/article/list.html.twig', [
            'articles' => $articleRepository->getArticlesWithComment(),
        ]);
    }
}
