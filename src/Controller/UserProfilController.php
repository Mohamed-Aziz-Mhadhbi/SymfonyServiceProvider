<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserProfilController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/home/profil", name="user_profil")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/user_profil/FreelancerProfil.html.twig', [
            'controller_name' => 'UserProfilController',
            'user' => $user
        ]);
    }
}
