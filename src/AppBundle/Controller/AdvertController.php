<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advert;
use AppBundle\Entity\Comment;
use AppBundle\Form\AdvertType;
use AppBundle\Form\CommentType;
use AppBundle\Services\BadgeManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Advert controller.
 *
 */
class AdvertController extends Controller
{
    /**
     * @Route("advert/new", name="advert_new")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advert->setAuthor($this->getUser());
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('advert_show', array(
                'id' => $advert->getId()
            ));
        }

        return $this->render('advert/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("advert/{id}", name="advert_show")
     * @param Request $request
     * @param $id
     * @param BadgeManager $badgeManager
     * @return Response
     */
    public function showAction(Request $request, $id, BadgeManager $badgeManager)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);
        $comments = $em->getRepository('AppBundle:Comment')->findByAdvert($id);

        if (null == $advert) {
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        /** Ajout d'un commentaire **/
        $comment = new Comment($advert, $this->getUser());
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $badgeManager->checkAndUnlockBadge('comment');

            return $this->redirect($request->getUri());
        }

        return $this->render('advert/show.html.twig', array(
            'advert' => $advert,
            'comments' => $comments,
            'form' => $form->createView()
        ));
    }
}
