<?php

namespace App\Repository;

use App\Entity\Notequiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notequiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notequiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notequiz[]    findAll()
 * @method Notequiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotequizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notequiz::class);
    }

    // /**
    //  * @return Notequiz[] Returns an array of Notequiz objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notequiz
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
