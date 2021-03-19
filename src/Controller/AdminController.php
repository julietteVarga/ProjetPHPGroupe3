<?php

namespace App\Controller;

use App\Entity\User;
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
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/signup', name: 'admin_signup')]
    public function signUp(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository): Response
    {
        $newUser = new User();
        $newUserForm = $this->createForm(SignUpType::class, $newUser);
        $newUserForm->handleRequest($request);

        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
           $userName= $newUser->getUsername();
           $userExist=$userRepository->findOneByUserName($userName);

           if(!$userExist) {
               //$newUser->setIsActive(false);

               //On encode le mot de passe :
               $password = $passwordEncoder->encodePassword($newUser, $newUser->getPassword());
               $newUser->setPassword($password);

               // tell Doctrine you want to eventually save the product (no queries yet) :
               $em->persist($newUser);
               // actually executes the queries (i.e. the INSERT query)
               $em->flush();

               $this->addFlash("success", "New User successfully saved !");
               return $this->render('user/homeSignedIn.html.twig', [
                   'newUser' => $newUser
               ]);
           }
        }

        return $this->render('admin/signUp.html.twig', [
            "newUserForm" => $newUserForm->createView()
        ]);
    }

    #[Route('/admin/userslist', name: 'admin_users_list')]
    public function usersList(): Response
    {


        return $this->render('admin/usersList.html.twig');
    }


    #[Route('/admin/campus', name: 'admin_campus')]
    public function campus(): Response
    {
        return $this->render('admin/manageCampus.html.twig');
    }

    #[Route('/admin/city', name: 'admin_city')]
    public function city(): Response
    {
        return $this->render('admin/manageCities.html.twig');
    }


}

