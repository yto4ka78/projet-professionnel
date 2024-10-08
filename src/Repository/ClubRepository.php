<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function countMembers($clubId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(u.id)')
            ->innerJoin('c.users', 'u')
            ->where('c.id = :clubId')
            ->setParameter('clubId', $clubId)
            ->getQuery()
            ->getResult();
    }

    /**
    * @return Club[] Returns an array of Club objects
    */
    public function findClubByOwner($userId): ?Club
    {
    return $this->createQueryBuilder('c')
        ->where('c.Owner = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('c.Name', 'ASC')
        ->getQuery()
        ->getResult();
    }
    

//    /**
//     * @return Club[] Returns an array of Club objects
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

//    public function findOneBySomeField($value): ?Club
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
