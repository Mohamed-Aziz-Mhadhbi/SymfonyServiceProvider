<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        return $this->render('forum/forum.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }

    /**
     * @Route ("/forum/publication", name="publication")
     */
    public function publicationActive()
    {
        return $this->render('forum/publication.html.twig');
    }
}
