<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advert;
use AppBundle\Form\AdvertType;
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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        if(null == $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        return $this->render('advert/show.html.twig', array(
            'advert' => $advert
        ));
    }
}
