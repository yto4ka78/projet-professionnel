<?php

namespace App\Repository;

use App\Entity\ClubRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClubRequest>
 *
 * @method ClubRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubRequest[]    findAll()
 * @method ClubRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClubRequest::class);
    }

//    /**
//     * @return ClubRequest[] Returns an array of ClubRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClubRequest
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
