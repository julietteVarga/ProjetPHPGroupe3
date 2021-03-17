<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route('/signup', name: 'user_signUp')]
    public function signUp(Request $request, EntityManagerInterface $em): Response
    {
        $newUser = new User();
        $newUserForm = $this->createForm(SignUpType::class, $newUser);
        $newUserForm->handleRequest($request);

        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
            $newUser->setIsActive(false);
            $userRole = new Role();
            $userRole->setIsAdmin(false);
            $newUser->setRole($userRole);

            // tell Doctrine you want to eventually save the product (no queries yet) :
            $em->persist($newUser);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $this->addFlash("success", "New User successfully saved !");
            return $this->redirectToRoute("user_profile", ["id" => $newUser->getId()]);
        }

        return $this->render('user/signUp.html.twig', [
            "newUserForm" => $newUserForm->createView()
        ]);
    }

    #[Route('/signin', name: 'user_signIn')]
    public function signin(AuthenticationUtils $utils): Response
    {
        $registeredUser = new User();
        $registeredUserForm = $this->createForm(SignInType::class, $registeredUser);
        return $this->render('user/signIn.html.twig', [
            "registeredUserForm" => $registeredUserForm->createView(),
            'loginError' => $utils->getLastAuthenticationError(),
            'loginUsername' => $utils->getLastUsername(),
        ]);
    }

    #[Route('/logout', name: 'user_signOut')]
    public function signout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/home/{id}', name: 'user_home', requirements: ['id' => '\d+'])]
    public function home(EntityManagerInterface $em, int $id): Response
    {
        $repository = $em->getRepository(User::class);
        $newUser  = $repository->find($id);
        dump($newUser);
        return $this->render('user/homeSignedIn.html.twig', [
            'newUser' => $newUser
        ]);
    }

    #[Route('/myprofile/{id}', name: 'user_profile', requirements: ['id' => '\d+'])]
    public function myProfile(EntityManagerInterface $em, int $id): Response
    {
        $repository = $em->getRepository(User::class);
        $newUser  = $repository->find($id);
        dump($newUser);
        return $this->render('user/myProfile.html.twig', [
            'newUser' => $newUser
        ]);
    }


}
