<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->render('blog/home/index.html.twig');
    }

    /**
     * @Route("article/{id}", name="article_show")
     * @param $id
     * @return Response
     * @internal param Request $request
     */
    public function showAction($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (null == $article) {
            throw new NotFoundHttpException("L'article n'existe pas");
        }

        return $this->render('blog/article/show.html.twig', array(
            'article' => $article,
        ));
    }
}
