<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('save', SubmitType::class)
            ->addEventListner(FormEvents::POST_SUBMIT,function(FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();
            })
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
