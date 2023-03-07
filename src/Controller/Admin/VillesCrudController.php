<?php

namespace App\Controller\Admin;

use App\Entity\Villes;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VillesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Villes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
        ];
    }

}
