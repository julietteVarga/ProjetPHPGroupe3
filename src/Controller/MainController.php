<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return Response
     * Fonction qui renvoie sur la page de connexion si l'utilisateur n'est pas connecté,
     * et sur la page d'accueil (avec la liste des sorties et les filtres de recherche) si le user est connecté
     */
    #[Route('/', name: 'main_index')]
    public function index(): Response
    {
        if ($this->getUser()) {
            //TODO changer le lien pour mettre a jour les etats selon la date du jour et renvoyer vers user_home
            return $this->redirectToRoute('update_outing');

        }else {
            return $this->redirectToRoute('app_login');
        }

    }



}
