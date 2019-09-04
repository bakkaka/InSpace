<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\PropertyRepository;
use App\Service\UploaderHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{

  /**
     * @Route("/admin/property/list", name="admin_property_list")
     */
    public function list(PropertyRepository $propertyRepository): Response
    {
        //$user = $this->getUser();

        return $this->render('admin/property/list.html.twig', [
            'properties' => $propertyRepository->findAll(),
         

        ]);
    }

  
    /**
     * @Route("/admin/property/new", name="admin_property_new")
     
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
	    $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        

            $em->persist($property);
            $em->flush();

            $this->addFlash('success', 'Property Created! Yours is power!');

            return $this->redirectToRoute('admin_property_list');
        }

        return $this->render('admin/property/new.html.twig', [
		'property' => $property, 
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}/edit", name="admin_property_edit")
    
     */
    
	public function edit(Property $property, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PropertyType::class, $property);
           
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
		     
            $em->persist($property);
            $em->flush();
            $this->addFlash('success', 'Property Updated! Inaccuracies squashed!');

            return $this->redirectToRoute('admin_property_list', [
			 'property' => $property,
                'id' => $property->getId(),
            ]);
        }

        return $this->render('admin/property/edit.html.twig', [
            'form' => $form->createView(),
			'property' => $property,
        ]);
    }
	

	
}
