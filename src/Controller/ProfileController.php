<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;
use App\Form\AvatarUploadFile;
use App\Form\BackGroundAvatarUploadFile;
use App\Form\SettingProfileFormType;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{

    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $userid = $user->getId();
            $clubsHost = $clubRepository->findBy(['Owner' => $userid]);
            $clubs = $user->getClubs();

            return $this->render('profile/profile.html.twig', [
                'clubsHost' => $clubsHost,
                'user' => $user,
                'clubs' => $clubs
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/modifier_profile/{id}', name: 'app_profile_setting')]
    public function profile_setting(int $id, Request $request,UserPasswordHasherInterface $passwordEncoder, UserRepository $userrepository): Response
    {
        /** @var \App\Entity\User|null $userActual */
        $userActual = $this->security->getUser();
        $user = $userrepository->find(['id' => $id]);

        if (!$userActual || $userActual->getId() !== $user->getId()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(SettingProfileFormType::class, $user);
        $form->handleRequest($request);

        $formAvatar = $this->createForm(AvatarUploadFile::class, $user);
        $formAvatar->handleRequest($request);

        $formBackGround = $this->createForm(BackGroundAvatarUploadFile::class, $user);
        $formBackGround->handleRequest($request);

        if (!$user instanceof User) {
            $this->addFlash('console', 'Suka blyat.');
            return $this->redirectToRoute('app_profile');
        }

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $user->eraseCredentials();
        }

        
        if ($formBackGround->isSubmitted() && $formBackGround->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $user->eraseCredentials();
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/profile_setting.html.twig',
          ['user' => $user,
           'form' => $form->createView(),
           'formAvatar' => $formAvatar->createView(),
           'formBackGround' => $formBackGround->createView(),]);
    }


    #[Route('/profile/{id}', name: 'app_profile_user')]
    public function regerderProfile(int $id, Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if ($user) {
            $userid = $user->getId();
            $clubsHost = $clubRepository->findBy(['Owner' => $userid]);
            $clubs = $user->getClubs();

            return $this->render('profile/profile.html.twig', [
                'clubsHost' => $clubsHost,
                'clubs' => $clubs,
                'user' => $user,
            ]);
        }
    }

}
