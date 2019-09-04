<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Entity\Author;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Comment;

use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     * @param ArticleRepository $articleRepository
     * @return Response
	 * @return App\Entity\Article
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();

        return $this->render('blog/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            //'allarticles' => $articleRepository->getArticleWithUser($user),

        ]);
    }


    public function listAction(): Response
    {
        $user = $this->getUser();
        return $this->render('blog/admin.html.twig', [

            //'allarticles' => $articleRepository->getArticleWithUser($user),
            //'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("show/article{id}", name="blog_show", methods={"GET"})

     */
    public function show( ArticleRepository $articleRepository,CommentRepository $commentRepository, Request $request, $id,EntityManagerInterface $em): Response
    {
        $article = $articleRepository->find($id);
		 $article->incrementVisitCount();
        $em->flush();
        //return new JsonResponse(['visits' => $article->getVisitCount()]);
        
        $comments = $commentRepository
            ->getCommentWithArticle($article);
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $id));
        }
		
        
        return $this->render('blog/show.html.twig', [
            'article' => $article,
           'comments' => $comments

        ]);
    }

    /**
     * @Route("/article/new", name="article_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {

        // $user = $this->getUser();
        $article = new Article();


        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article Created! Knowledge is power!');


            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/author/create", name="article_author_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {

        // $user = $this->getUser();
        $author = new Author();


        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $article->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your an Author!'
            );

            return $this->redirectToRoute('blog_show');
        }

        return $this->render('blog/author.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/news/{id}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger,EntityManagerInterface $em)
    {
        // TODO - actually heart/unheart the article!
        $logger->info('Article is being hearted!');
        $article->incrementHeartCount();
        $em->flush();
        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
	/**
     * @Route("/article/{id}/visit", name="article_count_visit", methods={"POST"})
     */
    public function ArticleCountVisit(Article $article, LoggerInterface $logger,EntityManagerInterface $em)
    {
        // TODO - actually heart/unheart the article!
        $logger->info('Article is being hearted!');
        $article->incrementVisitCount();
        $em->flush();
        return new JsonResponse(['visits' => $article->getVisitCount()]);
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Article $article, Request $request): Response
    {
        //$this->denyAccessUnlessGranted('EDIT', $article);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your resource has been modified!'
            );

            return $this->redirectToRoute('blog', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('blog/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("article/{id}/delete", name="article_delete")
     */

    public function delete(Article $article, Request $request): Response
    {
        //$this->denyAccessUnlessGranted('DELETE', $article);

        $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }


    public function menu(ArticleRepository $articleRepository): Response
    {
        return $this->render('blog/menu.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
     //@Security("is_granted('ROLE_USER')")
    /**
     * @Route("/addComment/article/{id}", name="blog_addcomment", methods={"GET","POST"})

     */

    public function addComment(Article $article, Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository, $id): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('App:Article')->findOneById($id);


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $entityManager->getRepository('App:Article')->findOneById($id);
            $comment->setUser($user);
            $article->setAuthor($user);
            $comment->setArticle($article);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your comment has been added!'
            );

            return $this->redirectToRoute('blog_show', array('id' => $article->getId()));
            //return $this->redirectToRoute($this->generateUrl('home_show', array('id' => $article->getId())));
        }

        return $this->render('blog/addComment.html.twig', [
            $comments = $commentRepository
                ->getCommentWithArticle($article),
            'article' => $articleRepository->findOneById($id),
            'form' => $form->createView(),
        ]);
    }


}
