<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Form\RegistrationFormType;
use App\Service\GlobalFormService;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postrepository): Response
    {
        $allPosts = $postrepository->findAll();
        // MIXE ARRAY
        shuffle($allPosts);
        $posts = array_slice($allPosts, 0, 2);
        return $this->render('main/main.html.twig',[
            'posts' => $posts
        ]);
    }
}


