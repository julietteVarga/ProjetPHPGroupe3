<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\CampusRepository;
use App\Repository\OutingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends AbstractController
{
    /**
     * @return Response
     * Fonction qui renvoie sur la page de connexion si l'utilisateur n'est pas connecté,
     * et sur la page d'accueil (avec la liste des sorties et les filtres de recherche) si le user est connecté
     */
    #[Route('/', name: 'main_index')]
    public function index(EntityManagerInterface $entityManager, CampusRepository $campusRepository, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('update_state');

        }else {

            return $this->redirectToRoute('app_login');
        }

    }



}
