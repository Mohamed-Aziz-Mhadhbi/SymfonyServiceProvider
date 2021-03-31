<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
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
     * @Route("/home/profile", name="profile")
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'users' => $userRepository->findByRoleField('prestataire'),
            'user' => $user
        ]);
    }

    /**
     * @Route("/home/profile/{id}", name="user_show_front", methods={"GET"})
     */
    public function showFront(User $user): Response
    {
        $userSession = $this->security->getUser();
        return $this->render('FrontInterface/profile/show.html.twig', [
            'userProfile' => $user,
            'skills' => $user->getSkills(),
            'user' => $userSession,
        ]);
    }

}
