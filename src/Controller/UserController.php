<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(UserRepository $userRepository, Request $request)
    {

         $user = new User();

        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $use = $form->getData();
            //$user->setUser($this->getUser());
            $em->persist($user);
            $em->flush();
            // return a blank form after success
            if ($request->isXmlHttpRequest()) {
                return $this->render('registration/register.html.twig', [
                    'user' => $user
                ]);
            }
            $this->addFlash('notice', 'Reps crunched!');
            return $this->redirectToRoute('user/list');
        }
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
            //->findBy(array('user' => $this->getUser()));
        // $totalWeight = 0;
        // foreach ($repLogs as $repLog) {
        //$totalWeight += $repLog->getTotalWeightLifted();
        //}
        // render just the form for AJAX, there is a validation error
        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
            return new Response($html, 400);
        }
        return $this->render('user/list.html.twig', array(
            'registrationForm' => $form->createView(),
            'users' => $users,
            //'leaderboard' => $this->getLeaders(),
            //'totalWeight' => $totalWeight,
        ));
    }
    /**
     * @Route("user/list", name="user_list")
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("user/{id}/delete", name="user_delete")
     */

    public function delete(User $user, Request $request): Response
    {
        //$this->denyAccessUnlessGranted('DELETE', $user);

        $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }

}
