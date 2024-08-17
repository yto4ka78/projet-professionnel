<?php 

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use App\Repository\PostRepository;



class PostController extends AbstractController
{
    private function checkUser (int $idClub, User $user, ClubRepository $clubRepository):bool
    {
        $club = $clubRepository->find($idClub);
        if (!$club)
        {
            return false;
        } 
        $userid = $user->getId();
        $owner = $club->getOwner();
        if ($owner !== $userid) {
            return false;
        }
        return true;
    }

    #[Route('/add_post/{id}', name: 'add_post', methods: ['POST'])]
    public function addPost(int $id, Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository): JsonResponse
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('home');
        }
        $club = $clubRepository->find($id);
        if(!$this->checkUser($id, $user, $clubRepository)){
            return new JsonResponse(['success' => false, 'message' => 'Vous etes pas createur']);
        }

        $messagePhoto = null;
        $content = $request->request->get('content');
        $minLength = 10;
        $maxLength = 1000;

        if (mb_strlen($content) < $minLength || mb_strlen($content) > $maxLength) {
            return new JsonResponse(['success' => false, 'message' => 'Le contenu doit contenir de ' . $minLength . ' à ' . $maxLength . ' symboles.'
            ]);
        }

        $imageFiles = $request->files->get('images');
        if (count($imageFiles) > 10) {
            return new JsonResponse([
                'success' => false, 'message' => 'Vous ne pouvez pas télécharger plus de 10 fichiers.']);
        }

        $post = new Post();
        $post->setContent($content);
        $post->setClub($club);

        $imagesDirectory = $this->getParameter('images_directory');
        if ($imageFiles) {
            $imageNames = [];
            foreach ($imageFiles as $imageFile) {
                //Make new name for images
                $filename = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move($imagesDirectory, $filename);
                $imageNames[] = '/uploads/images/' . $filename;
            }
            $post->setImages($imageNames);
            $messagePhoto = 'Фотки загружены';
        }

        $entityManager->persist($post);
        $entityManager->flush();
        
        return new JsonResponse([
            'messagePhoto' => $messagePhoto,
            'success' => true,
            'post' => [
                'club' => [
                    'name' => $club->getName(),
                    'region' => $club->getRegion()
                ],
                'date' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'content' => $post->getContent(),
                'images' => $post->getImages()
            ]
        ]);
    }

    #[Route('/post_all_photos/{id}', name: 'post_all_photos', methods: ['GET'])]
    public function showPhotos(int $id, PostRepository $postRepository)
    {

        $post = $postRepository->find($id);
        if(!$post) {
            return new JsonResponse([
                'post' => ['success'=> false, 'error' => 'PROBLEM']
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'post' => [
                'images' => $post->getImages()
            ]
        ]);
    }

}

