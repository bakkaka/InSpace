<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Sante;

use App\Form\SanteType;
use App\Repository\SanteRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{

    /**
     * @Route("/search", name="ajax_search")
     * @param Sante $sante
     * @param Request $request
     * @param SanteRepositoy $santeRepository
     * @return Response
     */
    public function searchAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $santes = $em->getRepository('App:Sante')->findAllSantes($requestString);
        if (!$santes) {
            $result['santes']['error'] = "keine Einträge gefunden";
        } else {
            $result['santes'] = $this->getRealEntities($santes);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = $entity->getName();
        }
        return $realEntities;
    }

    /**
     * @Route("show/sante{id}", name="sante_show", methods={"GET"})
     * @param Sante $sante
     * @param SanteRepository $santeRepository
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Sante $sante,SanteRepository $santeRepository, Request $request, $id): Response
    {
        $sante = $santeRepository->find($id);
       // $comments = $commentRepository
            //->getCommentWithArticle($article);
        if (!$sante) {
            throw $this->createNotFoundException(sprintf('No sante for id "%s"', $id));
        }
        //$comment = new Comment();
        // dump($article);die;
        return $this->render('ajax/show.html.twig', [
            'sante' => $sante,
            //'comments' => $comments

        ]);
    }

    /**
     * @Route("/ajax/sante/new", name="admin_article_new")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {

        // $user = $this->getUser();
        $sante = new Sante();


        $form = $this->createForm(SanteType::class, $sante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $article->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sante);
            $em->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your an Author!'
            );

            return $this->redirectToRoute('/ajax');
        }

        return $this->render('ajax/new.html.twig', [
            'sante' => $sante,
            'form' => $form->createView(),
        ]);
    }
}

