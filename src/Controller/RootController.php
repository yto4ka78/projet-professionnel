<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;

use App\Form\AvatarUploadFile;
use App\Form\SettingProfileFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RootController extends AbstractController
{

    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/root', name: 'app_root_panel')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('root/root_main.html.twig', [
            'user' => $user,
        ]);
    }

}
