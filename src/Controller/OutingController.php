<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use App\Form\SearchOutingType;
use App\Repository\OutingRepository;
use Doctrine\ORM\EntityManager;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Outing;
use App\Form\OutingType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Time;

class OutingController extends AbstractController
{

    public function findStateInCreation(EntityManagerInterface $entityManager): object
    {
        $repositoryS = $entityManager->getRepository(State::class);
        $stateInCreation = $repositoryS->findOneBy([
            'label' => 'En création'
        ]);

        return $stateInCreation;

    }

    public function findStateOpen(EntityManagerInterface $entityManager): object
    {
        $repositoryS = $entityManager->getRepository(State::class);
        $stateOpen = $repositoryS->findOneBy([
            'label' => 'Ouverte'
        ]);

        return $stateOpen;

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
        //On convertit en secondes.
        $dateNowStamp = $dateNow->getTimestamp();

        $repository = $em->getRepository(Outing::class);
        $listOutings = $repository->findAll();
        $repositoryS = $em->getRepository(State::class);


        //Reprendre les etats dans la base de données
        $closed = $repositoryS->findOneBy([
            'label' => 'Clôturée'
        ]);
        $started = $repositoryS->findOneBy([
            'label' => 'En cours'
        ]);

        $creation = $repositoryS->findOneBy([
            'label' => 'En création'
        ]);

        $archive = $repositoryS->findOneBy([
            'label' => 'Archivée'
        ]);

        $open = $repositoryS->findOneBy([
            'label' => 'Ouverte'
        ]);

        //Pour chaque sorties dans la base de données, on change l'état selon sa date.
        foreach ($listOutings as $outing) {
            $state = $outing->getState();
            $duration = $outing->getDuration();
            //On convertis en secondes
            $durationSeconds = $duration->getTimeStamp();


            $startingDate = $outing->getStartingDateTime();
            //On convertis la date de début en timestamp
            $startingDateStamp = $startingDate->getTimeStamp();


            //on prend la date d'aujourd'hui
            $archiveNow = new \DateTime('now');
            //on la convertis en timestamp
            $archiveStamp = $archiveNow->getTimeStamp();
            //on lui ajoute 30jours
            $dateArchiveToUse = strtotime('+30 days', $archiveStamp);

            $durationAndStartingDate = $startingDateStamp + $durationSeconds;


            //Si la date d'aujourd'hui est inférieure à la date de début
            //et supérieur à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est ouverte.
            if ($startingDateStamp > $dateNowStamp && $dateNowStamp > $durationAndStartingDate && $state != $creation) {
                $outing->setState($open);

            }
            //Si la date d'aujourd'hui est supérieure à la date de début
            //et supérieur à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est fermée.
            if ($startingDateStamp < $dateNowStamp && $dateNowStamp > $durationAndStartingDate && $state != $creation) {
                $outing->setState($closed);

            }
            //Si la date d'aujourd'hui est supérieure à la date de début
            //et inférieure à la durée + date de début et que l'état n'est pas en création
            //alors la sortie est en cours.
            if ($dateNowStamp > $startingDateStamp && $dateNowStamp < $durationAndStartingDate && $state != $creation) {
                $outing->setState($started);
            }
            //Si l'état est en création.
            if ($state === $creation) {
                $outing->setState($creation);
            }

            //Si la date d'aujourd'hui est supérieure à la date de début
            //et supérieur à la durée + date de début et que l'état n'est pas en création
            //et que la date de début est inférieure ou égale à la date d'archive (today +30jours)
            //alors la sortie est archivée.
            if ($startingDateStamp < $dateNowStamp && $dateNowStamp > $durationAndStartingDate
                && $startingDateStamp <= $dateArchiveToUse && $state != $creation) {
                $outing->setState($archive);
            }
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('user_home');

        }
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

        if ($outingForm->isSubmitted() && $outingForm->isValid() && $outingForm->get('save')->isClicked()) {

            $state = $this->findStateInCreation($entityManager);

            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($state);

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie enregistrée !');

            return $this->redirectToRoute('create_outing');

        } elseif ($outingForm->isSubmitted() && $outingForm->isValid() && $outingForm->get('saveAndAdd')->isClicked()) {

            $stateOpen = $this->findStateOpen($entityManager);
            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($stateOpen);

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



    #[Route('/modify-outing/{id}', name : 'modify_outing', requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id) :Response {

        $repositoryCampus = $entityManager->getRepository(User::class);
        $repository = $entityManager->getRepository(Outing::class);

        $campusOrganizer = $repositoryCampus->findOneBy(['username' => $this->getUser()->getUsername()]);
        $campusId = $campusOrganizer->getCampus();

        $outing = $repository->find($id);

        $outingForm = $this->createForm(OutingType::class, $outing);

        $outingForm->handleRequest($request);

        if ($outingForm->isSubmitted() && $outingForm->isValid() && $outingForm->get('save')->isClicked()) {

            $state = $this->findStateInCreation($entityManager);

            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($state);

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute('main_index');

        } elseif ($outingForm->isSubmitted() && $outingForm->isValid() && $outingForm->get('saveAndAdd')->isClicked()) {

            $stateOpen = $this->findStateOpen($entityManager);
            $outing->setOrganizer($campusOrganizer);
            $outing->setCampusOrganizer($campusId);
            $outing->setState($stateOpen);

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie publiée !');

            return $this->redirectToRoute('main_index');

        }

        return $this->render('outing/modifyOuting.html.twig', [
            'modifyForm' => $outingForm->createView(),
        ]);



    }


}
