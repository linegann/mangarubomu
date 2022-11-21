<?php

namespace App\Controller\Admin;

use App\Entity\Manga;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class MangaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Manga::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label'),
            AssociationField::new('characters')
            ->autocomplete()
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getCharacters()->toArray()); // ici getBodyShapes()
                }),
        ];
    }
    
}
