<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\QuestionQuiz;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteQuizType extends AbstractType
{
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->categorie = null;
        $this->difficulte = null;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $data = $options['data'];
        $this->categorie = $data['categorie'];
        $this->difficulte =  $data['difficulte'];
        $builder
            
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event ){
                $form = $event->getForm();

                $quizes =$this->em->getRepository(Quiz::class)->findByCatAndDiff($this->categorie,$this->difficulte);
                if(count($quizes) > 0){
                    $r= rand(0, count($quizes)-1);
                    $quiz = $quizes[$r];
                    $quizquestions = $this->em->getRepository(QuestionQuiz::class)->findByQuiz($quiz->getId());
                    $questions=[];
                    foreach ($quizquestions as $q){
                        array_push($questions,$this->em->getRepository(Question::class)->findOneById($q->getQuestion()));
                    }
                    dump($questions);

                    foreach ($questions as $question){
                        $form->add($question->getEnonce(),ChoiceType::class,[
                            'choices'=>[
                                $question->getPropositionA()=>$question->getPropositionA(),
                                $question->getPropositionB()=>$question->getPropositionB(),
                            ],
                            'mapped'=>false
                        ]);
                    }
                    $form->add('Enregistrer',submitType::class);
                }else{
                    //return $this->redirectToRoute('categories');
                    //return redirect()->back();
                    //->withInput();('select_quiz');
                    //'select_quiz'
                }
            })
            //->add('envoyer' ,SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
