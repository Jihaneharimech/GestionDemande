<?php

namespace App\Controller\Admin;

use App\Entity\Appareil;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AppareilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appareil::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
        ];
    }
    
}
