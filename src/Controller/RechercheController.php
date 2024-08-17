<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clubRepository = $entityManager->getRepository(Club::class);
        $clubs = $clubRepository->findAll();

        return $this->render('recherche/recherche.html.twig', [
            'clubs' => $clubs
        ]);
    }

}


