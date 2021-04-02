<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username' ,TextType::class ,[
        'attr' => [
            'class' => 'form-control'

        ]])
            ->add('nomEntreprise', TextType::class ,[
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
            ->add('phone', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('adresse', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('secteur', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('type', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('specialisation', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('siteWeb', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('presentation', TextareaType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
            ->add('taille', TextType::class ,[
                'attr' => [
                    'class' => 'form-control'

                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
