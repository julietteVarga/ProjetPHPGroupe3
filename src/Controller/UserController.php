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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route('/signup', name: 'user_signUp')]
    public function signUp(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $newUser = new User();
        $newUserForm = $this->createForm(SignUpType::class, $newUser);
        $newUserForm->handleRequest($request);

        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
            $newUser->setIsActive(false);

            //On encode le mot de passe :
            $password = $passwordEncoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($password);

            //Par défaut on met le role ROLE_USER en appelant getRoles de la classe User:
            $newUser->getRoles();

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
        return $this->render('security/login.html.twig', [
            "registeredUserForm" => $registeredUserForm->createView(),
            'loginError' => $utils->getLastAuthenticationError(),
            'loginUsername' => $utils->getLastUsername(),
        ]);
    }


    #[Route('/home', name: 'user_home')]
    public function home(EntityManagerInterface $em, int $id): Response
    {

        return $this->render('user/homeSignedIn.html.twig', [

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
