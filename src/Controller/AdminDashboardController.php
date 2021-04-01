<?php

namespace App\Controller;

use App\Repository\DomainRepository;
use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(UserRepository $userRepository, DomainRepository $domainRepository): Response
    {
        $domains = $domainRepository->findAll();

        $registationMonthly = $userRepository->countByMonth('12');

        $countRegistration = [];


        foreach ($registationMonthly as $registrations)
        {
            $countRegistration[] = $registrations["count"];
        }

        $domainTitle = [];
        $domainColor = [];
        $domainCount = [];

        foreach ($domains as $domain)
        {
            $domainTitle[] = $domain->getTitle();
            $domainColor[] = $domain->getColor();
            $domainCount[] = count($domain->getDomainUser());
        }

        return $this->render('BackInterface/admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
            'users' => $userRepository->countAll(),
            'clients' => $userRepository->countByRole("client"),
            'entreprise' => $userRepository->countByRole("entreprise"),
            'freelancers' => $userRepository->countByRole("prestataire"),
            'domainTitle' => json_encode($domainTitle),
            'domainColor' => json_encode($domainColor),
            'domainCount' => json_encode($domainCount),
            'countRegistration' => json_encode($countRegistration),

        ]);
    }
}
