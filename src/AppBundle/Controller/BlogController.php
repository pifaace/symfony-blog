<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use AppBundle\Services\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Paginator $paginator
     * @return Response
     */
    public function indexAction(Paginator $paginator): Response
    {
        $page = $paginator->getPage();

        $em = $this->getDoctrine()->getManager();
        $articles = $paginator->getItemList($em->getRepository('AppBundle:Article'), $page);

        $nbPages = $paginator->countPage($articles);

        return $this->render('blog/home/index.html.twig', array(
            'articles' => $articles,
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    /**
     * @Route("article/{id}", name="article_show")
     * @param Request $request
     * @param $id
     * @return Response
     * @internal param Request $request
     */
    public function showAction(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);
        $comments = $em->getRepository('AppBundle:Comment')->findBy(array('article' => $article->getId()));
        $countComment = $em->getRepository('AppBundle:Comment')->getCountComment($article->getId());


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


        return $this->render('blog/article/show.html.twig', array(
            'article' => $article,
            'comments' => $comments,
            'countComment' => $countComment,
            'form' => $form->createView()
        ));
    }

}
