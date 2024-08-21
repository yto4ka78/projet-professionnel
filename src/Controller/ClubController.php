<?php 
namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\ClubRequest;
use App\Entity\Post;
use App\Form\CreerClubType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use App\Repository\ClubRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;




class ClubController extends AbstractController {

    #[Route('/club_profile/{id}', name: 'club_profile')]
    public function index(ClubRepository $clubRepository, int $id, EntityManagerInterface $entityManager, LoggerInterface $logger):Response 
    {
        $user = $this->getUser();
        $club = $clubRepository->find($id);

        if (!$club) {
            return $this->redirectToRoute('home');
        }
        $requestList = $entityManager->getRepository(ClubRequest::class)->findBy(['Club' => $club]);
        $existingRequest = $entityManager->getRepository(ClubRequest::class)->findOneBy([
            'User' => $user,
            'Club' => $club,
        ]);
        
        $requestSend = false;
        if ($existingRequest){
            $requestSend = true;
        }

        $userList = $club->getUsers();

        $isOwner = null;
        if ($user instanceof User) {
            $isOwner = $club->getOwner() === $user->getId();
        }

        $posts = $entityManager->getRepository(Post::class)->findBy(['club' => $club->getId()]);

        return $this->render('club/club_profile.html.twig',[
            'club'=>$club,
            'isOwner'=>$isOwner,
            'requestSend'=>$requestSend,
            'requestList'=>$requestList,
            'userList'=>$userList,
            'posts'=>$posts
        ]);
    }

    #[Route('/club/{id}/request', name: 'club_request')]
    public function requestJoinClub(int $id, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {
        $user = $this->getUser();
        $club = $clubRepository->find($id);
        
        $existingRequest = $entityManager->getRepository(ClubRequest::class)->findOneBy([
            'User' => $user,
            'Club' => $club,
        ]);

        if ($existingRequest) {

        } else {
            $clubRequest = new ClubRequest();
            $clubRequest->setUser($user);
            $clubRequest->setClub($club);

            $entityManager->persist($clubRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }

    //Delete request

    #[Route('/club/{id}/deleteRequest', name: 'club_delete_request')]
    public function deleteRequest(int $id, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {
        $user = $this->getUser();
        $club = $clubRepository->find($id);
        
        $existingRequest = $entityManager->getRepository(ClubRequest::class)->findOneBy([
            'User' => $user,
            'Club' => $club,
        ]);

        if ($existingRequest) {
            $entityManager->remove($existingRequest);
            $entityManager->flush();
        } else {
            return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
        }

        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }


    //ACCEPTE REQUEST

    
    #[Route('/club/{id}/accepteRequest', name: 'club_accepte_request')]
    public function accepteRequest(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }

        $existingRequest = $entityManager->getRepository(ClubRequest::class)->findOneBy([
            'id' => $id
        ]);
        if (!$existingRequest) {
            return $this->redirectToRoute('home');
        }
        $club = $existingRequest->getClub();

        $requestingUser = $existingRequest->getUser();
        if ($club->getOwner() !== $user->getId()) {
            return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
        }

        $club->addUser($requestingUser);
        $entityManager->remove($existingRequest);
        $entityManager->flush();
        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }

    //REFUSER LE REQUEST
    #[Route('/club/{id}/deniedRequest', name: 'club_denied_request')]
    public function deniedRequest(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }

        $existingRequest = $entityManager->getRepository(ClubRequest::class)->findOneBy([
            'id' => $id
        ]);
        if (!$existingRequest) {
            return $this->redirectToRoute('home');
        }
        $club = $existingRequest->getClub();
        $requestingUser = $existingRequest->getUser();
        if ($club->getOwner() !== $user->getId()) {
            return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
        }

        $entityManager->remove($existingRequest);
        $entityManager->flush();
        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }

    //MODIFIER CLUB

    #[Route('/modifier_profile_club/{id}', name: 'modifier_profile_club')]
    public function modifier_profile_club (int $id, ClubRepository $clubRepository, EntityManagerInterface $entityManager, Request $request): Response 
    {
        $user = $this->getUser();
        if (!$user instanceof User){
            return $this->redirectToRoute('club_profile', ['id' => $id]);
        }
        $club = $clubRepository-> find($id);
        $form = $this->createForm(CreerClubType::class, $club);

        
        if ($this->isGranted('ROLE_ROOT')){
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($club);
                $entityManager->flush();
                return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
            }        
        } elseif ($user->getId() === $club->getOwner()) 
        {
            $form->remove('Owner');
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($club);
                $entityManager->flush();
                return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
            }           
        } else {
            return $this->redirectToRoute('home');
        }
        
        return $this->render('club/modifier_club.html.twig',[
            'club' => $club,
            'form' => $form->createView()
        ]);
    }

    //DELETE USER FROM CLUB
    #[Route('/club/{idClub}/deleteUser/{idUser}', name: 'club_delete_user_request')]
    public function deleteUserFromClub (int $idUser, int $idClub, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }
        $userDeleted = $entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);
        $club = $entityManager->getRepository(Club::class)->findOneBy(['id' => $idClub]);
        
        if (!$club || !$userDeleted || $club->getOwner() !== $user->getId()) {
            return $this->redirectToRoute('home');
        }
        
        $club->removeUser($userDeleted);
        $entityManager->flush();
        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }

    //DELETE POST
    #[Route('/club/{idPost}/deletePost/{idClub}', name: 'club_delete_post')]
    public function deletePostFromClub (int $idPost, int $idClub, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }
        $postDeleted = $entityManager->getRepository(Post::class)->find(['id' => $idPost]);
        $club = $entityManager->getRepository(Club::class)->find(['id' => $idClub]);
        
        if (!$club || !$postDeleted || $club->getOwner() !== $user->getId()) {
            return $this->redirectToRoute('home');
        }

        $images = $postDeleted->getImages();
        if ($images) {
            foreach ($images as $image) {
                $imageName = basename($image);
                $filePath = $this->getParameter('images_directory') . '/' . $imageName ;
                if (file_exists($filePath)) {
                    unlink($filePath); 
                }
            }
        }
        
        $club->removePost($postDeleted);
        $entityManager->flush();
        return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
    }

    #[Route('/delete_club/{idClub}/', name: 'app_club_delete')]
    public function deleteClub (int $idClub, EntityManagerInterface $entityManager, ClubRepository $clubrepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }
        $club = $entityManager->getRepository(Club::class)->findOneBy(['id' => $idClub]);
        
        if (!$club || $club->getOwner() !== $user->getId()) {
            return $this->redirectToRoute('home');
        }
        
        $entityManager->remove($club);
        $entityManager->flush();
        return $this->redirectToRoute('app_profile');
    }

}