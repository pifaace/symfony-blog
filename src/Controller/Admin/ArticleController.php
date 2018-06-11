<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ArticleType;
use App\Services\FlashMessage;
use App\Services\Uploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * @Route("admin/article/new", name="article_new")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param FlashMessage $flashMessage
     * @param Uploader     $fileUploader
     *
     * @return Response
     */
    public function new(Request $request, FlashMessage $flashMessage, Uploader $fileUploader): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());

            if ($fileUploader->noImage($article->getImage())) {
                $article->setImage(null);
            }

            $em->persist($article);
            $em->flush();

            $flashMessage->createMessage($request, FlashMessage::INFO_MESSAGE, "L'article a été créé avec succès");

            return $this->redirectToRoute('admin-articles');
        }

        return $this->render('backoffice/article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{id}/edit", name="article_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param int          $id
     * @param FlashMessage $flashMessage
     * @param Uploader     $fileUploader
     *
     * @return Response
     */
    public function edit(Request $request, int $id, FlashMessage $flashMessage, Uploader $fileUploader): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App:Article')->find($id);
        $image = $article->getImage();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($fileUploader->noImage($article->getImage())) {
                $article->setImage(null);
            } elseif ($fileUploader->hasNewImage($article->getImage())) {
                if (!null == $image) {
                    $em->remove($image);
                }

                $image = new Image();
                $image->setFile($article->getImage()->getFile());
                $article->setImage($image);
            } elseif ($fileUploader->isDeleteImageChecked($form->getData())) {
                $article->setImage(null);
                $em->remove($image);
            }
            $em->flush();

            $flashMessage->createMessage($request, FlashMessage::INFO_MESSAGE, "L'article a été mis à jour avec succès");

            return $this->redirect($request->getUri());
        }

        return $this->render('backoffice/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'currentImage' => $image,
        ]);
    }

    /**
     * @Route("admin/article/{id}/delete", name="article_delete")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param int          $id
     * @param FlashMessage $flashMessage
     *
     * @return Response
     */
    public function delete(Request $request, int $id, FlashMessage $flashMessage): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App:Article')->find($id);

        $em->remove($article);
        $em->flush();

        $flashMessage->createMessage($request, FlashMessage::INFO_MESSAGE, "L'annonce été supprimé avec succès");

        return $this->redirectToRoute('admin-articles');
    }
}
