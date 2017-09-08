<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Form\ArticleType;
use AppBundle\Form\CommentType;
use AppBundle\Services\BadgeManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{
    /**
     * @Route("article/new", name="article_new")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $article->setAuthor($this->getUser());
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_show', array(
                'id' => $article->getId()
            ));
        }

        return $this->render('article/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("article/{id}", name="article_show")
     * @param Request $request
     * @param $id
     * @param BadgeManager $badgeManager
     * @return Response
     */
    public function showAction(Request $request, $id, BadgeManager $badgeManager)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);
        $comments = $em->getRepository('AppBundle:Comment')->findByArticle($id);

        if (null == $article) {
            throw new NotFoundHttpException("L'article n'existe pas");
        }

        /** Ajout d'un commentaire **/
        $comment = new Comment($article, $this->getUser());
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'comments' => $comments,
            'form' => $form->createView()
        ));
    }
}
