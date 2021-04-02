<?php

namespace App\Repository;

use App\Entity\Project;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;
require 'D:\finale\ServiceProvider\vendor\twilio\sdk\src\Twilio\autoload.php';

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[] Returns an array of Question objects
     */
    public function calcul($status)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.status = :val')
            ->setParameter('val', $status)
            ->getQuery()
            ->getResult()
            ;

    }
    public  function sms(){



// Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'AC39ba69eb1f4e4622b672ac8ec975bd57';
        $auth_token = 'cac12932a3c6fa5e5b839f840f0ea267';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
        $twilio_number = "+14076244917";

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
// Where to send a text message (your cell phone?)
            '++21653944634',
            array(
                'from' => $twilio_number,
                'body' => 'You have a new project proposal'
            )
        );

    }

    public function findbytitle($title)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.title = :val')
            ->setParameter('val', $title)
            ->getQuery()
            ->getResult()
            ;

    }
}
