<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuestionController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/Question", name="question_index_back", methods={"GET"})
     */
    public function indexBack(QuestionRepository $questionRepository): Response
    {
        return $this->render('BackInterface/question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("home/question", name="question_index_front", methods={"GET"})
     */
    public function indexFront(QuestionRepository $questionRepository): Response
    {
        return $this->render('FrontInterface/question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/Question/new", name="question_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index_back');
        }

        return $this->render('BackInterface/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("home/question/new", name="question_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index_front');
        }

        return $this->render('FrontInterface/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/Question/{id}", name="question_show_back", methods={"GET"})
     */
    public function showBack(Question $question): Response
    {
        return $this->render('BackInterface/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("home/question/{id}", name="question_show_front", methods={"GET"})
     */
    public function showFront(Question $question): Response
    {
        return $this->render('FrontInterface/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/admin/dashboard/Question/{id}/edit", name="question_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index_back');
        }

        return $this->render('BackInterface/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("home/question/{id}/edit", name="question_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index_front');
        }

        return $this->render('FrontInterface/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/Question/{id}", name="question_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index_back');
    }

    /**
     * @Route("home/question/{id}", name="question_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index_front');
    }
}
