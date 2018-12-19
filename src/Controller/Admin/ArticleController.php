<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\Article\Manager\ArticleManager;
use App\Services\FlashMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * @var TranslatorInterface
     */
    private $trans;

    public function __construct(ArticleManager $articleManager, TranslatorInterface $trans)
    {
        $this->articleManager = $articleManager;
        $this->trans = $trans;
    }

    /**
     * @Route("admin/article/new", name="article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FlashMessage $flashMessage): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleManager->create($article);
            $flashMessage->createMessage(
                $request,
                FlashMessage::INFO_MESSAGE,
                $this->trans->trans('backoffice.articles.flashmessage_publish'));

            return $this->redirectToRoute('admin-articles');
        }

        return $this->render('backoffice/article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{slug}/edit", name="article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, FlashMessage $flashMessage): Response
    {
        $image = $article->getImage();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleManager->edit($article);

            $flashMessage->createMessage(
                    $request,
                    FlashMessage::INFO_MESSAGE,
                    $this->trans->trans('backoffice.articles.flashmessage_edit')
                );

            return $this->redirect($request->getUri());
        }

        return $this->render('backoffice/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'currentImage' => $image,
        ]);
    }

    /**
     * @Route("admin/article/{slug}/delete", name="article_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Article $article, FlashMessage $flashMessage): Response
    {
        $this->articleManager->remove($article);

        $flashMessage->createMessage(
            $request,
            FlashMessage::INFO_MESSAGE,
            $this->trans->trans('backoffice.articles.flashmessage_deleted_article')
        );

        return $this->redirectToRoute('admin-articles');
    }
}
