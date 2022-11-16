<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Characters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;


class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),

            AssociationField::new('characters')
                ->autocomplete()
                ->setFormTypeOption('by_reference', false)
                ->setTemplatePath('admin/album/characters.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {

    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
    ;
    }
}