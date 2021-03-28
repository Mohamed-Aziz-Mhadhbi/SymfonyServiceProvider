<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FreelancerFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('nom', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('prenom', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('email', EmailType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('password', PasswordType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])

            ->add('phone', NumberType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('photo', FileType::class)
            ->add('bio', TextareaType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('montantHoraire', NumberType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])

            ->add('skills')
            ->add('domains')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
