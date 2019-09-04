<?php

namespace App\Controller;

use App\Entity\RepLog;

use App\Form\RepLogType;
use App\Repository\RepLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxCallController extends AbstractController
{
    /**
     * @Route("/ajax/call", name="ajax_call")
     */
    public function index(RepLogRepository $repLogRepository)
    {
        return $this->render('ajax_call/index.html.twig', [
            'repLogs' => $repLogRepository->findAll()
            
        ]);
    }

    /**
     * @Route("ajax/new", name="ajax_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {

         $user = $this->getUser();
        $repLog = new RepLog();


        $form = $this->createForm(RepLogType::class, $repLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repLog->setUser($user);
            //dd($form->getData());
            //if (isset($data)) {
            //    dd($data);
            //}
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($repLog);
            $entityManager->flush();
            $this->addFlash('success', 'RepLog Created! Knowledge is power!');


            return $this->redirectToRoute('ajax_call');
        }

        return $this->render('ajax_call/new.html.twig', [
            'repLog' => $repLog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajax/call/{id}/delete", name="ajax_call_delete")
     */
    public function delete(RepLog $repLog)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $em = $this->getDoctrine()->getManager();
        $em->remove($repLog);
        $em->flush();
        return new Response(null, 204);
    }

}
