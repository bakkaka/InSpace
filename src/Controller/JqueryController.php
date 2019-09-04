<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JqueryController extends AbstractController
{
    /**
     * @Route("/jquery", name="jquery")
     */
    public function index()
    {
        return $this->render('jquery/index.html.twig', [

        ]);
    }
}
