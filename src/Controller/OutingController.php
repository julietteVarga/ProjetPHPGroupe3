<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Outing;
use App\Form\OutingType;
use Symfony\Component\Validator\Constraints\Time;

class OutingController extends AbstractController
{
    #[Route('/outing', name: 'outing')]
    public function index(): Response
    {
        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
        ]);
    }

    public function findState(EntityManagerInterface $entityManager): object
    {
        $repositoryS = $entityManager->getRepository(State::class);
        $stateInCreation = $repositoryS->findOneBy([
            'label' => 'Ouverte'
        ]);

        return $stateInCreation;

    }


    /**
     * Fonction pour mettre à jour nos états de sorties selon la date du jour.
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/updtateOuting', name : 'update_outing')]
    public function updateState(EntityManagerInterface $em): Response
    {
        $dateNow = new \DateTime('now');
        //On convertis en secondes.
        $dateNowStamp = $dateNow->getTimestamp();

dd($dateNow);


        $repository = $em->getRepository(Outing::class);
        $listOutings = $repository->findAll();
        $repositoryS = $em->getRepository(State::class);



        //Reprendre les etats dans la base de données
        $closed = $repositoryS->findOneBy([
            'label' => 'Clôturée'
        ]);
        $started =  $repositoryS->findOneBy([
            'label' => 'En cours'
        ]);

        $creation =  $repositoryS->findOneBy([
            'label' => 'En création'
        ]);

        $archive =  $repositoryS->findOneBy([
            'label' => 'Archivée'
        ]);

        $open =  $repositoryS->findOneBy([
            'label' => 'Ouverte'
        ]);




        //TODO Voir pourquoi l'égalité ne fonctionne pas et voir la notion de durée, changer la variable.

        //Pour chaque sorties dans la base de données, on change l'état selon sa date.
        foreach ($listOutings as $outing) {
            $state = $outing->getState();
            $duration = $outing->getDuration();
            //On convertis en secondes
            $durationSeconds = $duration->getTimeStamp();

            $startingDate = $outing->getStartingDateTime();
            //On convertis la date de début en timestamp
            $startingDateStamp = $startingDate->getTimeStamp();

            $durationAndStartingDate = $startingDateStamp + $durationSeconds;



            //Si la date d'aujourd'hui est inférieure à la date de début
            //et supérieur à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est ouverte.
            if($startingDateStamp>$dateNowStamp && $dateNowStamp>$durationAndStartingDate  && $state!=$creation){
                $outing->setState($open);

            }
            //Si la date d'aujourd'hui est supérieure à la date de début
            //et supérieur à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est fermée.
            if ($startingDateStamp < $dateNowStamp && $dateNowStamp>$durationAndStartingDate && $state!=$creation) {
                $outing->setState($closed);

            }
            //Si la date d'aujourd'hui est supérieure à la date de début
            //et inférieure à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est en cours.
            if ($dateNowStamp > $startingDateStamp && $dateNowStamp<$durationAndStartingDate && $state!=$creation ) {
                $outing->setState($started);
            }
            //Si l'état est en création.
            if ($state===$creation) {
                $outing->setState($creation);
            }

        }
        $em->persist($outing);
        $em->flush();
        return $this->redirectToRoute('user_home');

    }

    #[Route('/create-outing', name : 'create_outing')]
    public function create(Request $request, EntityManagerInterface $entityManager) :Response {

        $repository = $entityManager->getRepository(User::class);
        $campusOrganizer = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $campusId = $campusOrganizer->getCampus();

        $outing = new Outing();

        $outingForm = $this->createForm(OutingType::class, $outing);

        $outingForm->handleRequest($request);
        $dateNow = new \DateTime('now');

        if ($outingForm->isSubmitted() && $outingForm->isValid() && $outing->getStartingDateTime()> $dateNow &&
            $outing->getRegistrationDeadLine() <= $outing->getStartingDateTime() ) {

            $state = $this->findState($entityManager);

            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($state);

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie publiée !');

            return $this->redirectToRoute('create_outing');
        }
        else{

        }

        return $this->render('outing/createOuting.html.twig', [
            'outingForm' => $outingForm->createView(),
        ]);


    }
}
