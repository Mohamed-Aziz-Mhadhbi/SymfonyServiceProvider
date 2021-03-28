<?php

namespace App\Form;

use App\Entity\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => ['placeholder' => 'Enter the Title of the quiz here',
                    'class' => 'form-control'
                ]
            ])
            ->add('total_question',NumberType::class,[
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('difficulty')
            ->add('skill')
            ->add('quizUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
