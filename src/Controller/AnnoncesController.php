<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index(): Response
    {
        return $this->render('annonces/annonces.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }
    /**
     * @Route("/annonces/reponse", name="reponse")
     */
    public function reponseActive(): Response
    {
        return $this->render('annonces/reponseAnnonces.html.twig');
    }
}
