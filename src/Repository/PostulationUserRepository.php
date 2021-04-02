<?php

namespace App\Repository;

use App\Entity\PostulationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostulationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostulationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostulationUser[]    findAll()
 * @method PostulationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostulationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostulationUser::class);
    }

    // /**
    //  * @return PostulationUser[] Returns an array of PostulationUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostulationUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
