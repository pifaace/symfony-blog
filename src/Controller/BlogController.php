<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Services\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Cache(smaxage="5")
     *
     * @param Paginator $paginator
     *
     * @return Response
     */
    public function indexAction(Paginator $paginator): Response
    {
        $page = $paginator->getPage();

        $em = $this->getDoctrine()->getManager();
        $articles = $paginator->getItemList($em->getRepository('App:Article'), $page);

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
     * @param Request $request
     * @param $id
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showAction(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App:Article')->find($id);
        $comments = $em->getRepository('App:Comment')->findBy(['article' => $article->getId()]);
        $countComment = $em->getRepository('App:Comment')->getCountComment($article->getId());

        $newComment = new Comment();

        if (null == $article) {
            throw new NotFoundHttpException("L'article n'existe pas");
        }

        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newComment->setArticle($article);
            $em->persist($newComment);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('blog/article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'countComment' => $countComment,
            'form' => $form->createView(),
        ]);
    }
}
