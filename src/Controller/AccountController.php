<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @IsGranted("ROLE_ADMIN")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     *
     */
    public function index(ArticleRepository $articleRepository)
    {

        $author = $this->getUser();
        return $this->render('account/index.html.twig', [
            //'articles' => $articleRepository->findAll(),
            'articles' => $articleRepository->getArticleWithAuthor($author)

            
        ]);
    }


    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }
}
