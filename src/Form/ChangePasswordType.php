<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password',PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe actual',
                    'class' => 'form-control mt-5'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le nouveau mot d passe et la confirmation doivent identique',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe',
                    'class' => 'form-control mt-4'
                ]
            ],
                'second_options' => ['label' => false,
                'attr' => [
                    'placeholder' => 'Confirmer votre nouveau mot de passe',
                    'class' => 'form-control mt-4'
                ]
            ],
            ])
            ->add('Submit', SubmitType::class,[
                'label' => "Mettre à jour",
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
