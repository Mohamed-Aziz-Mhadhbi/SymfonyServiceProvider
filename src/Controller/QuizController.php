<?php

namespace App\Controller;

use App\Repository\QuestionQuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
class QuizController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/quiz", name="quiz")
     */
    public function index(QuestionQuizRepository $qz): Response
    {
        $quizes = $qz->findAll();
        return $this->render('quiz/quiz.html.twig', [
            'quizes'=> $quizes
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/new", name="new_quiz")
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {

        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
       
      $form->handleRequest($request);
        if($form->isSubmitted()){

            
            $quiz->setDifficulte($form["difficulte"]->getData());
            $quiz->setNbrQuestion($form["nbr_question"]->getData());
            $quiz->setNoteMax($form["note_max"]->getData());
            $quiz->setCategorie($form["categorie"]->getData());

            $errors = $validator->validate($question);
            if (count($errors) > 0) {
                
                $errorsString = (string) $errors;
        
                return $this->render('question/new.html.twig', [
                    "form"=> $form->createView(),
                    "errors"=> $errors
                ]);
            }
            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Question ajouté avec succés!');
            return $this->redirectToRoute('questions');
        }
        return $this->render('question/new.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
}
