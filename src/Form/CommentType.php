<?php

namespace App\Form;

use App\Entity\Comment;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('content',CKEditorType::class)
            ->add('likes')
            ->add('statusLike')
            ->add('creatAt')
            ->add('usr')
            ->add('pst',HiddenType::class)
            ->add('rating',IntegerType::class,[
            'attr' => [
                'min' => 0,
                'max' => 5,
                'step' => 1
            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
