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
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
     /**
     * @Route("/admin/dashboard/question/export", name="export_questions")
     */
    public function index(QuestionRepository $qr): Response
    {
        $questions = $qr->findAll();
        
        return $this->render('question/index.html.twig', [
            'questions' => $questions,
           
        ]);
        return new Response(
            $this->get('knp_snappy.pdf')->generateFromHtml($html, 'questions.pdf', [
                'page-size' => 'A4',
                'viewport-size' => '1280x1024',
                'margin-top' => 1,
                'margin-right' => 1,
                'margin-bottom' => 1,
                'margin-left' => 1,
                'enable-javascript' => true,
                'no-stop-slow-scripts' => true,
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
                'cookie' => array(),
                'dpi' => 300,
                'enable-external-links' => true,
                'enable-internal-links' => true
            ]);
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename='name.pdf'"
            )
        );
    }
     /**
     * @Route("/admin/dashboard/question/recherche", name="search_question")
     */
    public function search(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $requestString=$request->get('searchValue');
        $questions = $repository->findSQuestionByEnonce($requestString);
        $jsonContent = $Normalizer->normalize($questions, 'json',['groups'=>'questions']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
    }
}
