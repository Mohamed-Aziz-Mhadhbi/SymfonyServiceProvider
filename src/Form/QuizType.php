<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('difficulte', ChoiceType::class, [
                'choices'=> [ 'facile'=>'facile', 'moyenne'=>'moyenne', 'difficile'=>'difficile']
            ])
            ->add('nbr_question')
            ->add('note_max')
            ->add('categorie')
            ->add('quizQuestions',CollectionType::class, [
               'allow_add'=>true,
                'entry_type' => QuestionType::class
                
                ],)
            ->add('generate', SubmitType::class)
            // ->addEventListener(FormEvents::PRE_SUBMIT,function(FormEvent $event){
            //     $form = $event->getForm();
            //     $data = $event->getData();
            //    dump($data);
            //     // for ($i=1; $i <= $data[nbrQuestion]; $i++) { 
            //     //     $form->add('question_'.$i,EntityType::class,[
            //     //         'class' => Question::class,
            //     //         'mapped'=>false,
                        
            //     //     ]);
            //     // }
            //     $form->add('save', SubmitType::class);
            //})
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
