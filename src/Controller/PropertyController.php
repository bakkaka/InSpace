<?php

namespace App\Controller;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
        
    }
    /**
     * @Route("/property", name="property")
     */
    public function index(PropertyRepository $propertyRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig', [
           
            'properties'   => $properties,
            'form'         => $form->createView()
           

        ]);
    }
	
	/**
     * @Route("show/property{id}", name="property_show", methods={"GET"})

     */
    public function show(PropertyRepository $propertyRepository, Request $request, $id,EntityManagerInterface $em): Response
    {
        $property = $propertyRepository->find($id);
		 
            
        if (!$property) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $id));
        }
		
        
        return $this->render('property/show.html.twig', [
            'property' => $property,
          

        ]);
    }
	
	
	/**
     * @Route("/property/new", name="property_new")
   
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {

        // $user = $this->getUser();
        $property = new Property();


        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // $article->setUser($user);
            
            
            $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($property);
            $entityManager->flush();
            $this->addFlash('success', 'Immobilier Crée avec succés! votre proprieté est votre bien!');


            return $this->redirectToRoute('property');
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }
	
	  /**
     * @Route("/property/{id}/edit", name="property_edit")
   
     */
	
	 public function edit(Property $property, Request $request, EntityManagerInterface $em): Response
    {

        
        //$this->denyAccessUnlessGranted('EDIT', $property);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice', 'Congratulations!!, your resource has been modified!'
            );

            return $this->redirectToRoute('property_edit', [
                'id' => $property->getId(),
            ]);

        }
		
		return $this->render('property/edit.html.twig.', [
		     'porperty' => $property,
            'form' => $form->createView()
        ]);
	}
  
  /**
     * @Route("/admin/property/{id}/delete", name="admin_property_delete")
	  
     */

    public function delete(Property $property, Request $request): Response
    {
       // $this->denyAccessUnlessGranted('DELETE', $article);

        $entityManager = $this->getDoctrine()->getManager();
         $entityManager->remove($property);
        $entityManager->flush();

        return $this->redirectToRoute('admin_property_list');
    }
  
}
