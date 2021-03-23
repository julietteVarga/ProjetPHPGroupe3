<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\State;
use App\Entity\User;
use App\Form\SearchOutingType;
use App\Repository\OutingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Outing;
use App\Form\OutingType;

class OutingController extends AbstractController
{

    public function findState(EntityManagerInterface $entityManager): object
    {
        $repository = $entityManager->getRepository(State::class);
        $stateInCreation = $repository->findOneBy([
            'label' => 'En création'
        ]);

        return $stateInCreation;

    }


    #[Route('/create-outing', name : 'create_outing')]
    public function create(Request $request, EntityManagerInterface $entityManager) :Response {

        $repository = $entityManager->getRepository(User::class);
        $campusOrganizer = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $campusId = $campusOrganizer->getCampus();


        $outing = new Outing();
        $outingForm = $this->createForm(OutingType::class, $outing);
        $outingForm->handleRequest($request);

        if ($outingForm->isSubmitted() && $outingForm->isValid()) {

            $state = $this->findState($entityManager);

            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($state);

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie publiée !');

            return $this->redirectToRoute('create_outing');
        }

        return $this->render('outing/createOuting.html.twig', [
            'outingForm' => $outingForm->createView(),
        ]);
    }

    #[Route('/showOuting/{id}', name: 'outing_show',  requirements: ['id' => '\d+'])]
    public function showOrganizer(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $repository = $em->getRepository(Outing::class);
        $outing  = $repository->find($id);
        return $this->render('outing/outingPage.html.twig', [
            'outing' => $outing
        ]);
    }


    public function filterOutings(OutingRepository $repository) {

    }


}
