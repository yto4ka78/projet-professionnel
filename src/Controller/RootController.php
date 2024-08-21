<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;
use App\Form\SettingProfileFormType;
use App\Form\AvatarUploadFile;
use App\Form\BackGroundAvatarUploadFile;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;


class RootController extends AbstractController
{

    #[Route('/root', name: 'app_root_panel')]
    #[IsGranted('ROLE_ROOT')]
    public function index(Request $request, ClubRepository $clubrepository): Response
    {
        $user = $this->getUser();
        $clubs = $clubrepository->findAll();
    
        $content = $request->request->get('form_name');
        $clubs = $clubrepository->findAll();

        return $this->render('root/root_main.html.twig', [
            'clubs' => $clubs,
            'user' => $user,
        ]);
    }

    #[Route('/search_clubs', name: 'search_clubs', methods: ['POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function searchClubs(Request $request, ClubRepository $clubRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $name = $data['name'] ?? '';
            $region = $data['region'] ?? '';
            $id = $data['id'] ?? '';
            $page = $data['page'] ?? 1; 
            $limit = 10; 

            $queryBuilder = $clubRepository->createQueryBuilder('c');

            if ($name) {
                $queryBuilder->andWhere('c.Name LIKE :name')
                    ->setParameter('name', '%' . $name . '%');
            }

            if ($region) {
                $queryBuilder->andWhere('c.Region LIKE :region')
                    ->setParameter('Region', '%' . $region . '%');
            }

            if ($id) {
                $queryBuilder->andWhere('c.id LIKE :id')
                    ->setParameter('id', '%' . $id . '%');
            }

            $queryBuilder->setFirstResult(($page - 1) * $limit) 
                     ->setMaxResults($limit);
            $clubs = $queryBuilder->getQuery()->getResult();

            $countQueryBuilder = clone $queryBuilder;
            $totalClubs = $countQueryBuilder->select('COUNT(c.id)')
            ->setFirstResult(null)  // For save this -> function setFirstResult(($page - 1) * $limit)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();

            $totalPages = ceil($totalClubs / $limit);

            $clubList = [];
            foreach ($clubs as $club) {
                $clubList[] = [
                    'id' => $club->getId(),
                    'name' => $club->getName(),
                    'region' => $club->getRegion(),
                    'Avatar' => $club->getAvatar(),
                ];
            }

            return new JsonResponse([
                'success' => true,  
                'clubs' => $clubList,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/search_user', name: 'search_user', methods: ['POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $name = $data['name'] ?? '';
            $id = $data['id'] ?? '';
            $page = $data['page'] ?? 1; 
            $limit = 10; 

            $queryBuilder = $userRepository->createQueryBuilder('c');

            if ($name) {
                $queryBuilder->andWhere('c.Name LIKE :name')
                    ->setParameter('name', '%' . $name . '%');
            }

            if ($id) {
                $queryBuilder->andWhere('c.id LIKE :id')
                    ->setParameter('id', '%' . $id . '%');
            }

            $queryBuilder->setFirstResult(($page - 1) * $limit) 
                     ->setMaxResults($limit);
            $users = $queryBuilder->getQuery()->getResult();

            $countQueryBuilder = clone $queryBuilder;
            $totalUser = $countQueryBuilder->select('COUNT(c.id)')
            ->setFirstResult(null)  // For save this -> function setFirstResult(($page - 1) * $limit)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();

            $totalPages = ceil($totalUser / $limit);

            $userlist = [];
            foreach ($users as $user) {
                $userlist[] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'Avatar' => $user->getAvatar(),
                ];
            }

            return new JsonResponse([
                'success' => true,  
                'users' => $userlist,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/modifier_profile_root/{id}', name: 'app_profile_setting_root')]
    #[IsGranted('ROLE_ROOT')]
    public function profile_setting_root(int $id, EntityManagerInterface $entityManager, Request $request,UserPasswordHasherInterface $passwordEncoder, UserRepository $userrepository): Response
    {
        $user = $userrepository->find(['id' => $id]);
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_profile');
        }
        
        $form = $this->createForm(SettingProfileFormType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);

        $formAvatar = $this->createForm(AvatarUploadFile::class, $user);
        $formAvatar->handleRequest($request);
        $formBackGround = $this->createForm(BackGroundAvatarUploadFile::class, $user);
        $formBackGround->handleRequest($request);

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $user->eraseCredentials();
        }
        
        if ($formBackGround->isSubmitted() && $formBackGround->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $user->eraseCredentials();
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user', ['id' => $user->getId()]);
        }

        return $this->render('root/profile_setting_root.html.twig',
          ['user' => $user,
           'form' => $form->createView(),
           'formAvatar' => $formAvatar->createView(),
           'formBackGround' => $formBackGround->createView(),]);
    }

    #[Route('/delete_user_root/{idUser}/', name: 'root_user_delete')]
    #[IsGranted('ROLE_ROOT')]
    public function deleteUserRoot (int $idUser, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);
        
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_profile');
    }


}
