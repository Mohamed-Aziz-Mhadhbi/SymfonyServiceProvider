<?php

namespace App\Form;

use App\Entity\Forum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'attr' => ['placeholder' => 'Enter the title here',
                    'class' => 'col-sm-2 col-form-label'
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr' => ['placeholder' => 'Enter the Description here',
                    'class' => 'form-control'
                    ]
                ])
            ->add('us')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
