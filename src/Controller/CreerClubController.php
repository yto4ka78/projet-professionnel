<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;
use App\Form\CreerClubType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;



class CreerClubController extends AbstractController
{
    #[Route('/creerclub', name: 'app_creerclub')]
    public function index(): Response
    {
        return $this->render('club/creerclub_info.html.twig');
    }

    #[Route('/creerclub_admin', name: 'app_creerclub_new')]
    public function newClub(EntityManagerInterface $entityManager, Request $request): Response
    {
        $club = new Club();
        $formClub = $this->createForm(CreerClubType::class, $club);
        $formClub->handleRequest($request);


        if ($formClub->isSubmitted() && $formClub->isValid()) {
                $entityManager->persist($club);
                $entityManager->flush();
                return $this->redirectToRoute('club_profile', ['id' => $club->getId()]);
                // $this->redirectToRoute('app_recherche');
        } else {
                $errorMessage = "Error";
        } 

        return $this->render('club/creerclub_new.html.twig',[
            'formClub' => $formClub->createView(),
        ]);
    }


    #[Route('/creerclub_admin_id/{searchValue}', name: 'app_creerclub_new_search_id',  methods: ['GET'])]
    public function search_owner(string $searchValue, EntityManagerInterface $entityManager): JsonResponse
    {
        $results = $entityManager->getRepository(User::class)->findBy(['id' => $searchValue]);

        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'avatar' => $result->getAvatar(),
                'id' => $result->getId(),
                'name' => $result->getName(),
            ];
        }
        
        return new JsonResponse($data);
    }

}


