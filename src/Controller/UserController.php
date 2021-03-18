<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class UserController extends AbstractController
{


    #[Route('/home', name: 'user_home')]
    public function home(): Response
    {
        return $this->render('user/homeSignedIn.html.twig');
    }

    #[Route('/user/myprofile', name: 'user_my_profile')]
    public function modifyMyProfile(int $id,Request $request,
                                    EntityManagerInterface $entityManager,
                                    UserRepository $userRepository): Response
    {
        $repository = $userRepository;
           $userModify= $repository->find($id);

        $userModifyForm = $this->createForm(UserType::class,$userModify);
        $userModifyForm->handleRequest($request);


        if($userModifyForm->isSubmitted() && $userModifyForm->isValid()){

            $entityManager->persist($userModify);
            $entityManager->flush();

            $this->addFlash('success','Vous avez bien modifié votre profil!');

            return $this->redirectToRoute('user_my_profile',['id'=>$id]);

        }

        return $this->render('user/myProfile.html.twig', [
            'userModifyForm' => $userModifyForm->createView(),


        ]);

    }


}
