<?php

namespace App\Controller;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $search_word = $request->query->get('form_name', '');
        $clubRepository = $entityManager->getRepository(Club::class);
        $clubs = $clubRepository->findAll();

        return $this->render('recherche/recherche.html.twig', [
            'clubs' => $clubs,
            'search_word' => $search_word
        ]);
    }

    #[Route('/search_clubs_all', name: 'search_clubs_all', methods: ['POST'])]
    public function searchClubs(Request $request, ClubRepository $clubRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true); //true for make array, not object

            $name = $data['name'] ?? '';
            $region = $data['region'] ?? '';
            $page = $data['page'] ?? 1; 
            $limit = 9; 

            $queryBuilder = $clubRepository->createQueryBuilder('c');

            if ($name) {
                $queryBuilder->andWhere('c.Name LIKE :name')
                    ->setParameter('name', '%' . $name . '%');
            }

            if ($region) {
                $queryBuilder->andWhere('c.Region LIKE :region')
                    ->setParameter('region', '%' . $region . '%');
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
                    'description' => $club->getDescription()
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

}


