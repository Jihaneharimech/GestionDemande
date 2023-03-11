<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\Villes;
use App\Entity\Demande;
use App\Entity\Appareil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomClient', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom Client',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse Email',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse Client',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code Postal',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville',
                'class' => Villes::class,
                'choice_label' => 'nom',
                 'expanded' => false,
                 'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('typeAppareil', EntityType::class, [
                'label' => 'Type Appareil',
                'class' => Appareil::class,
                'choice_label' => 'nom',
                 'expanded' => false,
                 'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('nbrAppareil', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre Appareils',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('dateDisponibilite', DateTimeType::class, [
                'label' => 'Date Installation',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => [
                    'placeholder' => 'Date Installation',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ;               
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
