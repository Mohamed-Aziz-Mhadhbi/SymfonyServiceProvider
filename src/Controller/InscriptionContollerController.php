<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionContollerController extends AbstractController
{
    /**
     * @Route("/inscription/contoller", name="inscription_contoller")
     */
    public function index(): Response
    {
        return $this->render('inscription_contoller/index.html.twig', [
            'controller_name' => 'InscriptionContollerController',
        ]);
    }
}
