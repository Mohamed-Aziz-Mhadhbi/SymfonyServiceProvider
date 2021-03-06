<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuizController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/quiz", name="quiz_index_back", methods={"GET"})
     */
    public function indexBack(QuizRepository $quizRepository): Response
    {
        return $this->render('BackInterface/quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/quiz", name="quiz_index_front", methods={"GET"})
     */
    public function indexFront(QuizRepository $quizRepository): Response
    {
        return $this->render('FrontInterface/quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/new", name="quiz_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_index_back');
        }

        return $this->render('BackInterface/quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/quiz/new", name="quiz_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_index_front');
        }

        return $this->render('FrontInterface/quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/{id}", name="quiz_show_back", methods={"GET"})
     */
    public function showBack(Quiz $quiz): Response
    {
        return $this->render('BackInterface/quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/home/quiz/{id}", name="quiz_show_front", methods={"GET"})
     */
    public function showFront(Quiz $quiz): Response
    {
        return $this->render('FrontInterface/quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/{id}/edit", name="quiz_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Quiz $quiz): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_index_back');
        }

        return $this->render('BackInterface/quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/quiz/{id}/edit", name="quiz_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Quiz $quiz): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_index_front');
        }

        return $this->render('FrontInterface/quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/{id}", name="quiz_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Quiz $quiz): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_index_back');
    }

    /**
     * @Route("/home/quiz/{id}", name="quiz_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Quiz $quiz): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_index_front');
    }

}
