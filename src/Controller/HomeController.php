<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Repository\PostulationRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
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
     * @Route("/home", name="home")
     */
    public function index(UserRepository $userRepository,PostRepository $postRepository,OffreRepository $offreRepository,ServiceRepository $serviceRepository,ForumRepository $forumRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/home/index.html.twig',[
            'user' => $user,
            'users' => $userRepository->findAll(),
            'posts' => $postRepository->findAll(),
            'offers' => $offreRepository->findAll(),
            'services' => $serviceRepository->findAll(),
            'forums' => $forumRepository->findAll(),
        ]);
    }
}
