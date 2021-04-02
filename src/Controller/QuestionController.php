<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\QuestionType;
use App\Entity\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


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
    function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator):Response{
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
       
        $form->handleRequest($request);
        if($form->isSubmitted()){

            $question = new Question();
            $question=$form->getData();
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
            $this->addFlash('success', 'Question ajoutée avec succés!');
            return $this->redirectToRoute('questions');
        }
        return $this->render('question/new.html.twig', [
            "form"=> $form->createView(),
        ]);

    }
    /**
     * @Route("/admin/dashboard/question/edit/{id}", name="edit_question")
     * @Method={"GET","POST"}
     */
    public function edit(Request $request, QuestionRepository $repository,  EntityManagerInterface $em,int  $id):Response{
        $question = $repository->findOneQuestionById($id);
        $form = $this->createForm(QuestionType::class, $question);
        $form-> handleRequest($request);
        if($form->isSubmitted()){
            $question = $form->getData();
            $em->flush();
            $this->addFlash('success', 'Question modifiée avec succés!');
            return $this->redirectToRoute('questions');   
        }
        return $this->render('question/edit.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/dashboard/question/delete/{id}", name="delete_question")
     * @Method({"DELETE"})
     */
   public function delete(Request $req, int $id, QuestionRepository $qr, EntityManagerInterface $em){
        $question = $qr->findOneQuestionById($id);
        $em->remove($question);
        $em->flush();
        $this->addFlash('success', 'Question supprimée avec succés!');
        return $this->redirectToRoute('questions');
    }
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
    }
}
