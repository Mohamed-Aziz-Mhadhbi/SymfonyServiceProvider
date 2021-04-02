<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CompleteQuizType;

use App\Repository\CategorieRepository;
use App\Repository\QuestionQuizRepository;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\QuizType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class QuizController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/quiz", name="quiz")
     */
    public function index(QuizRepository $qz): Response
    {
        $quizes = $qz->findAll();
        return $this->render('quiz/index.html.twig', [
            'quizes'=> $quizes
        ]);
    }

    /**
     * @Route("/admin/dashboard/quiz/new", name="new_quiz")
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {

        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz,['allow_extra_fields'=>true]);
        
       
      $form->handleRequest($request);
        if($form->isSubmitted()){
            $quiz= $form->getData();
            $errors = $validator->validate($quiz);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return $this->render('quiz/new.html.twig', [
                    "form"=> $form->createView(),
                    "errors"=> $errors
                ]);
            }
            $em->persist($quiz);
            $em->flush();
            $this->addFlash('success', 'quiz ajouté avec succés!');
            dump($form->getExtraData());
            return $this->redirectToRoute('quiz');
        }
        /*
            $quiz->setDifficulte($form["difficulte"]->getData());
            $quiz->setNbrQuestion($form["nbr_question"]->getData());
            $quiz->setNoteMax($form["note_max"]->getData());
            $quiz->setCategorie($form["categorie"]->getData());

            $errors = $validator->validate($quiz);
            if (count($errors) > 0) {
                
                $errorsString = (string) $errors;
        
                return $this->render('quiz/new.html.twig', [
                    "form"=> $form->createView(),
                    "errors"=> $errors
                ]);
            }
            $em->persist($quiz);
            $em->flush();
            $this->addFlash('success', 'Quiz ajouté avec succés!');
            return $this->redirectToRoute('quiz');
        }*/
        return $this->render('quiz/new.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
    /**
     * @Route("/quiz/show/{category}/{diff}", name="show_quiz")
     */
    public  function show(Request $request, QuizRepository $qz,CategorieRepository $cr, QuestionQuizRepository $qr, QuestionRepository $quesr,$category,$diff):Response{

        $cat =$cr->findOneByDisgnation($category);
        $quizes =$qz->findByCatAndDiff($cat->getId(),$diff);
        $r= rand(0, count($quizes)-1);
        $quiz = $quizes[$r];
        $quizquestions = $qr->findByQuiz($quiz->getId());
        $questions=[];
        foreach ($quizquestions as $q){
            array_push($questions,$quesr->findOneById($q->getQuestion()));
        }
        $form = $this->createForm(CompleteQuizType::class,$quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted()){

        }
        return $this->render('quiz/show.html.twig', [
            "form"=> $form->createView()

        ]);

    }

    /**
     * @Route("/quiz/select", name="select_quiz")
     */
    public function selectQuiz(Request $request):Response{
        $form = $this->createFormBuilder()
        ->add('categorie',EntityType::class,[
            'class'=>Categorie::class
        ])
        ->add('Selectionner' ,SubmitType::class)
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
                $data = $form->getData();
                $categorie = $data['categorie'];
                $quiz = new Quiz();
                $formQuiz=$this->createForm(CompleteQuizType::class, $quiz, [
                    'data'=>[
                        'categorie'=>$categorie,
                        'difficulte'=>'facile'
                    ],
                    'data_class'=>null
                    ]);
                $formQuiz->handleRequest($request);
                if($formQuiz->isSubmitted()){
                    $data = $formQuiz->getData();
                    dump($data);
                }
                return $this->render('quiz/selectQuiz.html.twig', [
                    "form"=> $formQuiz->createView()
                ]);

        }
        

        return $this->render('quiz/selectQuiz.html.twig', [
            "form"=> $form->createView()
        ]);
    }

}
