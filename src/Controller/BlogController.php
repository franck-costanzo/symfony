<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comments;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name:'home')]
    function home(): Response
        {
        return $this->redirectToRoute('articles');
        // return $this->render('blog/home.html.twig');
    }

    #[Route('/articles', name:'articles')]
    function articles(ManagerRegistry $doctrine, Request $request): Response
    {
        $pages = $request->get('page', 1);
        $entityManager = $doctrine->getManager();
        $articles = $entityManager->getRepository(Articles::class)->findAll();
        return $this->render('blog/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name:'article')]
    function article(ManagerRegistry $doctrine, $id): Response
        {
        $comment = new Comments();
        $comment->setUser($this->getUser());
        $form = $this->createForm(CommentType::class, $comment);
        $entityManager = $doctrine->getManager();
        $article = $entityManager->getRepository(Articles::class)->find($id);
        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'commentTypeForm' => $form->createView()
        ]);

    }
}
