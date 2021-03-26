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
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class
            ])

            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                $form = $event->getForm();
                $data =$form->getData();

               $category = $data->getCategorie();
               $diff = 'facile';



                $quizes =$this->em->getRepository(Quiz::class)->findByCatAndDiff($category->getId(),$diff);
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
                $form->add('terminer',submitType::class);
            })
            ->add('créer' ,SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
