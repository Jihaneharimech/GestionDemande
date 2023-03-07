<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomClient')
            ->add('adresse')
            ->add('codePostal')
            ->add('email')
            ->add('telephone')
            ->add('dateDisponibilite')
            ->add('nbrAppareil')
            ->add('statut')
            ->add('description')
            ->add('createdAt')
            ->add('ville')
            ->add('typeAppareil')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
