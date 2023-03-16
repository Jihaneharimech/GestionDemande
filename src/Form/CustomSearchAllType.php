<?php

namespace App\Form;

use App\Classe\CustomSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomSearchAllType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('stringSearchAll', TextType::class,[
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Rechercher......',
            ]
        ])
        ->add('Submit', SubmitType::class,[
            'label' => '<i class="fas fa-search"></i>',
            'label_html' => true,
            'attr' => [
                'class' => 'btn-primary ml-1 btnSearch '
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
