<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class InscriptionContollerController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription_contoller")
     * @Method ({"GET"})
     */
    public function index(): Response
    {
        return $this->render('inscription_contoller/index.html.twig', [
            'controller_name' => 'InscriptionContollerController',
        ]);
    }
}
