<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/quiz", name="quiz")
     */
    public function index(): Response
    {
        return $this->render('quiz/quiz.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }

    /**
     * @Route("/admin/dashboard/addQuiz", name="showAddQuiz")
     */
    public function showAddQuiz(): Response
    {
        return $this->render('quiz/addQuiz.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }
}
