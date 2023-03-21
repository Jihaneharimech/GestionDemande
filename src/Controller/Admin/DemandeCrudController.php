<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Demande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class DemandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Demande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $createdAt = new DateTimeImmutable();
        
        $demande = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomClient','Nom Client'),
            EmailField::new('email','Email'),
            TelephoneField::new('telephone'),
            TextField::new('adresse'),
            AssociationField::new('ville','Ville'),
            TextField::new('codePostal','Code Postal'),
            AssociationField::new('typeAppareil','Appareil installé'),
            IntegerField::new('nbrAppareil','Nombre Appareil'),
            DateTimeField::new('dateDisponibilite','Date instalation'),
            AssociationField::new('statut','Statut'),
            TextEditorField::new('description','Description'),
            DateTimeField::new('createdAt', 'Passée le')
            ->hideOnForm()
            ->setFormTypeOption('disabled', false)
            ->setFormTypeOption('data', $createdAt),
        ];

        return $demande;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('dateDisponibilite')
            ->add('createdAt')
        ;
    }
    
}
