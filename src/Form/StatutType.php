<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut', EntityType::class, [
                'label' => false,
                'class' => Statut::class,
                'choice_label' => 'nom',
                 'expanded' => false,
                 'attr' => [
                    'class' => 'form-select text-white btnstatut'
                 ],
                  'choice_attr' => function($statut, $key, $value) {
                   return ['style' => 'background-color:'.$statut->getColor().'; color:#fff',
                   'data-color' => $statut->getColor()
                ];
                    }
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
