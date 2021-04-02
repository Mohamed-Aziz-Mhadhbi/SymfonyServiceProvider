<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }
    /**
     * @param $title
     * @return int|mixed|string
     */
    function searchtitle($title){
        return $this->createQueryBuilder('o')
            ->where('.title LIKE :title')
            ->setParameter('title','%' . $title . '%')
            ->getQuery()->getResult();
    }

    /**
     * @param $data
     * @return int|mixed|string
     */
    public function search($data){

        $query = $this->createQueryBuilder('o');
        if($data){
            $query->andWhere('o.id LIKE :data OR
                 o.title LIKE :data OR
                 o.description LIKE :data OR
                 o.creatAt LIKE :data  ')
                ->setParameter('data','%'.$data.'%');
        }
        return $query
            ->getQuery()
            ->getResult();
    }

    public function stat1()
    {
        $rawSql = "SELECT o.domain_offer_id,count(o.id) as nbdom FROM Offre o group by o.domain_offer_id";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetchAll();
    }


    // /**
    //  * @return Offre[] Returns an array of Offre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offre
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
