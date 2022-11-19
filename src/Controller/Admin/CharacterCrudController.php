<?php

namespace App\Controller\Admin;

use App\Entity\Character;
use App\Entity\Manga;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class CharacterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Character::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('gender'),
            AssociationField::new('manga') // remplacer par le nom de l'attribut spécifique, par exemple 'bodyShape'
                ->formatValue(function ($value, $entity) {
                    return implode(', ', $entity->getManga()->toArray()); // ici getBodyShapes()
                    })

        ];
    }

}
