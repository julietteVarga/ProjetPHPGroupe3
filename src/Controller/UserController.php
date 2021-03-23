<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\Role;
use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\SignInFormAuthenticator;
use Doctrine\Common\Collections\ArrayCollection;
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






    #[Route('user/home', name: 'user_home')]
    public function home(Request $request, EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Outing::class);
        $allOutings = $repository->findAll();

        return $this->render('user/homeSignedIn.html.twig', [
            'allOutings' => $allOutings
        ]);
    }


    /**
     * fonction pour s'inscrire a une sorte lorqu'on est en session.
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    #[Route('user/signUpOuting/{id}', name: 'user_sign_up_outing', requirements: ['id' => '\d+'])]
    public function signUpOuting (Request $request, EntityManagerInterface $em, int $id) : Response
    {

        //on va cherche la sortie par rapport à son id qu'on a envoyé via le lien s'inscrire.
        $repository = $em->getRepository(Outing::class);
        $outing = $repository->find($id);


        //on va chercher l'utilisateur en session
        $repositoryU = $em->getRepository(User::class);
        $outingParticipant = $repositoryU->findOneBy(['username' => $this->getUser()->getUsername()]);

        //on crée un array collection pour pouvoir ajouter la sortie dans la relation user_outing.
        $collectionU= new ArrayCollection();
        $collectionU->add($outing);

        //on crée un array collection pour pouvoir ajouter l'utilisateur en session dans la relation user_outing.
        $collectionO= new ArrayCollection();
        $collectionO->add($outingParticipant);


        //si la sortie existe et si l'utilisateur en session existe alors on ajoute les arraycollection
        //dans l'entité correspondante et on flush tout ca.
        if($outing && $outingParticipant){
            $outing->setParticipants($collectionO);
            $outingParticipant->setOutingsParticipants($collectionU);
            $em->persist($outingParticipant);
            $em->persist($outing);

            $em->flush();

            $this->addFlash('sucess',"Participant ajouté!");
            return $this->redirectToRoute('user_home');
        }



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
            'userModifyForm' => $userModifyForm->createView()]);
    }





}
