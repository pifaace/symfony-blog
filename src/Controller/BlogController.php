<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Services\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Cache(smaxage="5")
     *
     * @param Paginator         $paginator
     * @param ArticleRepository $articleRepository
     *
     * @return Response
     */
    public function indexAction(Paginator $paginator, ArticleRepository $articleRepository): Response
    {
        $page = $paginator->getPage();
        $articles = $paginator->getItemList($articleRepository, $page);
        $nbPages = $paginator->countPage($articles);

        return $this->render('blog/home/index.html.twig', [
            'articles' => $articles,
            'nbPages' => $nbPages,
            'page' => $page,
        ]);
    }

    /**
     * @Route("article/{id}", name="article_show")
     * @Method({"GET", "POST"})
     *
     * @param Article $article
     *
     * @return Response
     */
    public function showAction(Article $article): Response
    {
        return $this->render('blog/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("article/{id}/comment/new", name="comment_new")
     * @Method("POST")
     *
     * @param Request $request
     * @param Article $article
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newCommentAction(Request $request, Article $article): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->addComment($comment);

            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    /**
     * @param Article $article
     *
     * @return Response
     */
    public function commentForm(Article $article): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/article/_comment_form.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }
}
