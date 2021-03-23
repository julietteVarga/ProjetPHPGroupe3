<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\User;
use App\Form\CampusType;
use App\Form\CityType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{

    #[Route('/admin/signup', name: 'admin_signup')]
    public function signUp(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder,
                           UserRepository $userRepository): Response
    {
        $newUser = new User();
        $newUserForm = $this->createForm(SignUpType::class, $newUser);
        $newUserForm->handleRequest($request);

        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
            $userName = $newUser->getUsername();
            $userExist = $userRepository->findOneByUserName($userName);

            if(!$userExist) {
            //On encode le mot de passe :
            $password = $passwordEncoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($password);

            // tell Doctrine you want to eventually save the product (no queries yet) :
            $em->persist($newUser);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $this->addFlash("success", "New User successfully saved !");
            return $this->redirectToRoute('admin_users_list', [
                'newUser' => $newUser
            ]);
            }else {
                $this->addFlash("notice", "Utilisateur déjà existant !");
            }
        }
        return $this->render('admin/signUp.html.twig', [
            "newUserForm" => $newUserForm->createView()
        ]);
    }

    #[Route('/admin/usersList', name: 'admin_users_list')]
    public function usersList(EntityManagerInterface $em, Request $request): Response
    {
        $repository = $em->getRepository(User::class);
        $allUsers = $repository->findAll();
        return $this->render('admin/usersList.html.twig', [
            'allUsers' => $allUsers
        ]);
    }

    #[Route('/admin/profile/{id}', name: 'admin_user_profile', requirements: ['id' => '\d+'])]
    public function userProfile(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $repository = $em->getRepository(User::class);
        $user = $repository->find($id);

        return $this->render('admin/adminUserProfile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/admin/campus', name: 'admin_campus')]
    public function campus(EntityManagerInterface $em, Request $request): Response
    {
        $repository = $em->getRepository(Campus::class);
        $allCampus = $repository->findAll();
        return $this->render('admin/manageCampus.html.twig', [
            'allCampus' => $allCampus
        ]);
    }


    #[Route('/admin/addCampus', name: 'admin_add_campus')]
    public function addCampus(Request $request, EntityManagerInterface $em): Response
    {
        $newCampus = new Campus();
        $newCampusForm = $this->createForm(CampusType::class, $newCampus);
        $newCampusForm->handleRequest($request);

        if ($newCampusForm->isSubmitted() && $newCampusForm->isValid()) {
            // tell Doctrine you want to eventually save the product (no queries yet) :
            $em->persist($newCampus);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $this->addFlash("success", "Nouveau Campus ajouté !");
            return $this->redirectToRoute('admin_campus', [
                'newCampus' => $newCampus
            ]);
        }
        return $this->render('admin/addCampus.html.twig', [
            "newCampusForm" => $newCampusForm->createView()
        ]);
    }

    #[Route('/admin/cities', name: 'admin_cities')]
    public function cities(EntityManagerInterface $em, Request $request): Response
    {
        $repository = $em->getRepository(City::class);
        $allCities = $repository->findAll();
        return $this->render('admin/manageCities.html.twig', [
            'allCities' => $allCities
        ]);

        return $this->render('admin/manageCities.html.twig');
    }


    #[Route('/admin/addCity', name: 'admin_add_city')]
    public function addCities(Request $request, EntityManagerInterface $em): Response
    {
        $newCity = new City();
        $newCityForm = $this->createForm(CityType::class, $newCity);
        $newCityForm->handleRequest($request);

        if ($newCityForm->isSubmitted() && $newCityForm->isValid()) {
            // tell Doctrine you want to eventually save the product (no queries yet) :
            $em->persist($newCity);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $this->addFlash("success", "Nouvelle ville ajoutée !");
            return $this->redirectToRoute('admin_cities', [
                'newCity' => $newCity
            ]);
        }
        return $this->render('admin/addCity.html.twig', [
            "newCityForm" => $newCityForm->createView()
        ]);
    }


}

