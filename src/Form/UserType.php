<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('role')
            ->add('password')
            ->add('createdAt')
            ->add('enabled')
            ->add('token')
            ->add('phone')
            ->add('photo')
            ->add('bio')
            ->add('nomEntreprise')
            ->add('adresse')
            ->add('secteur')
            ->add('type')
            ->add('specialisation')
            ->add('siteWeb')
            ->add('presentation')
            ->add('taille')
            ->add('montantHoraire')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
