<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\Villes;
use App\Entity\Appareil;
use App\Classe\CustomSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('string', TextType::class,[
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Je cherche...',
            ]
        ])
        ->add('ville', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Villes::class,
            'choice_label' => 'nom',
             'multiple' => true,
             'expanded' => false,
             'attr' => [
                'data-placeholder' => 'Sélectionner Ville(s)',
                'style' => 'font-weight: 400;margin-right: 11px;',
            ]
        ])
        ->add('typeAppareil', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Appareil::class,
            'choice_label' => 'nom',
             'multiple' => true,
             'expanded' => false,
             'attr' => [
                'data-placeholder' => 'Sélectionner Type(s) Appareils',
            ]
        ])
        ->add('statut', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Statut::class,
            'choice_label' => 'nom',
             'multiple' => true,
             'expanded' => false,
             'attr' => [
                'data-placeholder' => 'Sélectionner Statut(s)',
            ]
        ])
        ->add('datefrom', DateType::class,[
            'label' => 'De',
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('dateto', DateType::class,[
            'label' => 'À',
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('Submit', SubmitType::class,[
            'label' => "Afficher les résultats",
            'attr' => [
                'class' => 'btn-block btn-primary  '
            ]
        ])
    ;
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => CustomSearch::class,
            'methode' => 'GET',
            'crsf_protection' => false
        ]);
    }

}
