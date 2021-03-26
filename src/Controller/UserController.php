<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Outing;
use App\Entity\ProfilePicture;
use App\Entity\Role;
use App\Entity\User;
use App\Form\SearchOutingType;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Repository\OutingRepository;
use App\Repository\ProfilePictureRepository;
use App\Repository\UserRepository;
use App\Security\SignInFormAuthenticator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
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

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param OutingRepository $outingRepository
     * @return Response
     * Fonction qui va :
     * - chercher la liste de toutes les sorties (findAll) pour les afficher dans la page d'accueil lorsque le user est connecté
     * - créer le formulaire de recherche pour filtrer les sorties si le user le souhaite
     */
    #[Route('/user/home', name: 'user_home')]
    public function home(Request $request, EntityManagerInterface $em, OutingRepository $outingRepository): Response
    {
        $data = new SearchData();
        $userInSession = $this->getUser();
        $form = $this->createForm(SearchOutingType::class, $data);
        $form->handleRequest($request);

        $filterOutings = $outingRepository->findSearch($data, $userInSession);

        return $this->render('user/homeSignedIn.html.twig', [
            'filterOutings' => $filterOutings,
            'form' => $form->createView(),
        ]);

    }



    /**
     * Fonction pour ajouter une nouvelle image si l'utilisateur ne possède pas deja d'image
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param ProfilePictureRepository $profilePictureRepository
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addImage(Request $request,
                                    EntityManagerInterface $entityManager,
                                    UserRepository $userRepository,
                                    ProfilePictureRepository $profilePictureRepository,
                                    UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $userName = $user->getUsername();

        $repository = $userRepository;
        $user = $repository->findOneByUserName($userName);


        $userModify = $user[0];

        $userModifyForm = $this->createForm(UserType::class, $userModify);


        $userModifyForm->handleRequest($request);
        $pictureinDb= new ProfilePicture();
        $userInSession= $this->getUser();

        $userModify=$userInSession;

        $userModify->setUserName($userName);
        $plainPassword = $userModify->getPassword();
        $encoded = $encoder->encodePassword($userModify, $plainPassword);

        $userModify->setPassword($encoded);

        $profilePic = $userModify->getProfilePic();


        //On met un nom au hasard à la photo
        $picName = $this->generateUniqueFileName().'.'.$profilePic->guessExtension();

        // moves the file to the directory where pics are stored
        $profilePicPath =   $profilePic->move(
            $this->getParameter('profile_pic_directory'),
            $picName
        );

        // updates the 'profile pic' property to store the png file name
        // instead of its contents
        $userModify->setProfilePic($pictureinDb);

        $pictureinDb->setName($picName);
        $pictureinDb->setUserPic($userModify);
        $pictureinDb->setPath($profilePicPath);



        // ... persist the $product variable or any other work

        $entityManager->persist($userModify);
        $entityManager->persist($pictureinDb);
        $entityManager->flush();


        return $this->redirectToRoute('user_my_profile');

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
                                    UserRepository $userRepository,
                                    ProfilePictureRepository $profilePictureRepository,
                                    UserPasswordEncoderInterface $encoder): Response
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


                //On va cherche la photo de profile de l'utilisateur en session
                $picture = $profilePictureRepository->findOneByUserId($userModify->getId());
                //Si l'utilisateur n'a pas deja une image
                if (!$picture){
                    $this->addImage($request,$entityManager,$userRepository,$profilePictureRepository,$encoder);
                }
                else{
                $pictureinDb = $picture[0];

                $userInSession= $this->getUser();

                $userModify=$userInSession;

                $userModify->setUserName($userName);
                $plainPassword = $userModify->getPassword();
                $encoded = $encoder->encodePassword($userModify, $plainPassword);

                $userModify->setPassword($encoded);

                $profilePic = $userModify->getProfilePic();


                //On met un nom au hasard à la photo
                $picName = $this->generateUniqueFileName().'.'.$profilePic->guessExtension();

                // moves the file to the directory where pics are stored
             $profilePicPath =   $profilePic->move(
                 $this->getParameter('profile_pic_directory'),
                    $picName
                );
                // updates the 'profile pic' property to store the png file name
                // instead of its contents
                $userModify->setProfilePic($pictureinDb);

                $pictureinDb->setName($picName);
                $pictureinDb->setUserPic($userModify);
                $pictureinDb->setPath($profilePicPath);

                // ... persist the $product variable or any other work

                $entityManager->persist($userModify);
                $entityManager->persist($pictureinDb);
                $entityManager->flush();


                return $this->redirectToRoute('user_my_profile');

                }}

            }
        return $this->render('user/myProfile.html.twig', [
            'userModifyForm' => $userModifyForm->createView()]);
    }


    /**
     * fonction pour s'inscrire à une sortie lorqu'on est en session.
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

        $participantsList = $outing->getParticipants();
        $outingsList = $this->getUser()->getOutingsParticipants();

        $participantsList->add($outingParticipant);
        $outingsList->add($outing);

        //si la sortie existe et si l'utilisateur en session existe alors on ajoute les listes
        //dans l'entité correspondante et on flush tout ca.
        if($outing && $outingParticipant && count($participantsList)<=$outing->getMaxNumberRegistration()){
            $outing->setParticipants($participantsList);
            $outingParticipant->setOutingsParticipants($outingsList);
            $em->persist($outingParticipant);
            $em->persist($outing);
            $em->flush();
            $this->addFlash('success',"Vous êtes bien inscrit à la sortie \"".$outing->getName()."\" !");
            return $this->redirectToRoute('user_home');
        } else {
            $this->addFlash('notice', 'Vous ne pouvez pas vous inscrire');
            return $this->redirectToRoute('main_index');
        }

    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     * Fonction pour afficher le profil du user organisateur d'une sortie considérée lors du clic sur son nom dans le
     * tableau des sorties de la page d'accueil
     */
    #[Route('/user/showUser/{id}', name: 'user_show_organizer',  requirements: ['id' => '\d+'])]
    public function showOrganizer(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $repository = $em->getRepository(User::class);
        $user  = $repository->find($id);
        return $this->render('user/otherProfiles.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * fonction pour se désinscrire à une sortie lorqu'on est en session.
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    #[Route('user/signOutOuting/{id}', name: 'user_sign_out_outing', requirements: ['id' => '\d+'])]
    public function signOutOuting (Request $request, EntityManagerInterface $em, int $id) : Response
    {
        //on va chercher la sortie par rapport à son id qu'on a envoyé via le lien se désinscrire.
        $repository = $em->getRepository(Outing::class);
        $outing = $repository->find($id);

        //on va chercher l'utilisateur en session
        $repositoryU = $em->getRepository(User::class);
        $userInSession = $repositoryU->findOneBy(['username' => $this->getUser()->getUsername()]);

        //on récupère la liste des participants de la sortie considérée :
        $outingParticipantsList = $outing->getParticipants() ;
        $userOutings = $userInSession->getOutingsParticipants();

        //on retire l'utilisateur en session déclaré plus haut (userInSession) :
        $outingParticipantsList->removeElement($userInSession);
        $userOutings->removeElement($outing);


            $em->persist($outing);
            $em->persist($userInSession);
            $em->flush();
            $this->addFlash('sucess',"Vous vous êtes désinscrit de la sortie :".$outing->getName());
            return $this->redirectToRoute('user_home');

    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


}
