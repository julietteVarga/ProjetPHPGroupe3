<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\SignInFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
            return $this->redirectToRoute("user_my_profile", ["id" => $newUser->getId()]);
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

    /**
     * Fonction pour modifier son profil en tant qu'utilisateur en session.
     * Si le formulaire est bien rempli et renvoyé, on encode le mot de passe une nouvelle fois
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    #[Route('/user/myprofile', name: 'user_my_profile')]
    public function modifyMyProfile(Request $request,
                                    EntityManagerInterface $entityManager,
                                    UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $userName = $user->getUsername();

        $repository = $userRepository;
        $user = $repository->findOneByUserName($userName);

        $userModify = $user[0];

        $userModifyForm = $this->createForm(UserType::class, $userModify);

        $userModifyForm->handleRequest($request);


        if ($userModifyForm->isSubmitted() && $userModifyForm->isValid() ) {
            $allUsers = $repository->findAllExceptUserName($userModify->getUserName());
            foreach ($allUsers as $userTest){
                if($userModify->getUserName()===$userTest->getUsername()){

                    $user = $userModify;

                    $plainPassword = $user->getPassword();
                    $encoded = $encoder->encodePassword($user, $plainPassword);

                    $user->setPassword($encoded);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Vous avez bien modifié votre profil!');

                    return $this->redirectToRoute('user_my_profile');

                }

                $userInSession= $this->getUser();

                $userModify=$userInSession;

                $userModify->setUserName($userName);
                $plainPassword = $userModify->getPassword();
                $encoded = $encoder->encodePassword($userModify, $plainPassword);

                $userModify->setPassword($encoded);

                $entityManager->persist($userModify);
                $entityManager->flush();


                return $this->redirectToRoute('user_my_profile');
                }

            }


        return $this->render('user/myProfile.html.twig', [
            'userModifyForm' => $userModifyForm->createView(),


        ]);

    }


}
