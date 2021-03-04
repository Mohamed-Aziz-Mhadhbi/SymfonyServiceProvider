<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('difficulte')
            ->add('nbr_question')
            ->add('note_max')
            ->add('categorie')
            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event){
                $quiz = $event->getData();
                $form = $event->getForm();
                for($i =1;$i<= $quiz->getNbrQuestion();$i++){
                    $form->add('question'.$i, EntityType::class,[
                        'class'=>Question::class,
                        'choice_label'=> 'enonce',
                    ]);
                }
            } );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
