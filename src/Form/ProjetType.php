<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\SousCategorie;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,[
                'required' => true,
            ])
            ->add('description')
            ->add('competence')
            ->add('type' ,ChoiceType::class, [
                'choices'  => [
                    'Web' => "Web",
                    'php' => "php",
                    'css' => "css",
                ],
            ])

            ->add('sous_categorie', EntityType::class, [
                 'class' => SousCategorie::class,
                'choice_label' => 'nom',


])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
