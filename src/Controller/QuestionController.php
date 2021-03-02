<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\QuestionType;
use App\Entity\Question;

class QuestionController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/question/", name="questions")
     */
    public function index(QuestionRepository $qr): Response
    {
        $questions = $qr->findAll();
        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/admin/dashboard/question/new", name="new_question")
     */
    function new(Request $request, EntityManagerInterface $em):Response{
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
       
      $form->handleRequest($request);
        if($form->isSubmitted()){

            $question = new Question();
            /*$question->setEnonce($request->get("enonce"));
            $question->setPropositionA($request->get("proposition_A"));
            $question->setPropositionB($request->get("proposition_B"));
            $question->setPropositionCorrecte($request->get("proposition_correcte"));*/
            $question=$form->getData();
            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Question ajouté avec succés!');
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('question/new.html.twig', [
            "form"=> $form->createView(),
        ]);

    }
    /**
     * @Route("/admin/dashboard/question/{id}", name="edit_question")
     */
    public function edit(Request $request, QuestionRepository $repository,  EntityManagerInterface $em,int  $id):Response{
        $question = $repository->findOneQuestionById($id);
        $form = $this->createForm(QuestionType::class, $question);
        $form-> handleRequest($request);
        if($form->isSubmitted()){
            $question = $form->getData();
            $em->flush();
            //TODO redirect to admin page
        }
        return $this->render('question/edit.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
    /**
     * @Route("/question/{id}", name="question", method="delete")
     */
   /* public function delete(int $id, QuestionRepository $qr, EntityManagerInterface $em){
        $question = $qr->findOneQuestionById($id);
        $em->remove($question);
        $em->flush();
    }
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
    }*/
}
