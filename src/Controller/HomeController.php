<?php

namespace App\Controller;

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
    public function index(): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/home/index.html.twig',[
            'user' => $user
        ]);
    }
}
