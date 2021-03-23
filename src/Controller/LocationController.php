<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    #[Route('/location', name: 'location')]
    public function index(): Response
    {
        return $this->render('location/index.html.twig', [
            'controller_name' => 'LocationController',
        ]);
    }

    #[Route('/create-location', name : 'create_location')]
    public function create(Request $request, EntityManagerInterface $entityManager) :Response {

        $addLocation = new Location();
        $locationForm =  $this->createForm(LocationType::class, $addLocation);
        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {

            $entityManager->persist($addLocation);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajouté !');

            return $this->redirectToRoute('create_outing');
        }

        return $this->render('location/addLocation.html.twig', [
            'locationForm' => $locationForm->createView()

        ]);
    }
}
