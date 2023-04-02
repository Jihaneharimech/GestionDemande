<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usersChoices = $options['users_choices'] ?? [];
        $builder
        ->add('users', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => User::class,
            'choices' => $usersChoices,
            'choice_label' => 'name',
             'multiple' => true,
             'expanded' => false,
             'attr' => [
                'data-placeholder' => 'Rechercher...',
                'style' => 'font-weight: 400;margin-right: 11px;',
            ]
        ])
        ->add('Submit', SubmitType::class,[
            'label' => 'Valider',
            'label_html' => true,
            'attr' => [
                'class' => 'btn-primary ml-1 btnSearch '
            ]
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('users_choices');
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Demande::class,
            'methode' => 'GET',
            'crsf_protection' => false
        ]);
    }
}
