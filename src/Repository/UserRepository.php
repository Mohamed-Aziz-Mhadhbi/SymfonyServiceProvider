<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

     /**
     * @return User[] Returns an array of User objects
     */
    public function findByRoleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAll()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countByRole($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = :val')
            ->setParameter('val', $value)
            ->select('count(u)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function CountByMonth($months)
    {
        $start = new \DateTime();
        $start->sub(new \DateInterval("P{$months}M"));

        return $this->createQueryBuilder('u')
            ->select('MONTH(u.createdAt) AS month, count(u.id) AS count')
            ->where('u.createdAt between :last_registration_start and :last_registration_end')
            ->setParameter('last_registration_start',$start)
            ->setParameter('last_registration_end',new \DateTime())
            ->groupBy('month')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $data
     * @return int|mixed|string
     */
    public function search($data){

        $query = $this->createQueryBuilder('u');
        if($data){
            $query->andWhere('u.id LIKE :data OR
                 u.nom LIKE :data OR
                 u.prenom LIKE :data OR
                 u.specialisation LIKE :data OR
                 u.username LIKE :data  ')
                ->setParameter('data','%'.$data.'%');
        }
        return $query
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
