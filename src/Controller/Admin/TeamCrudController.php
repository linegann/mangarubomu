<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Entity\Membre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureFields(string $pageName): iterable
    {

    return [
        IdField::new('id')->hideOnForm(),
        AssociationField::new('creator'),
        BooleanField::new('published'),
        TextField::new('description'),

        AssociationField::new('characters')
        ->hideWhenCreating() // on ne souhaite pas gérer l'association entre les [objets] et la [galerie] dès la crétion de la [galerie]
        ->setTemplatePath('admin/fields/album_characters.html.twig')
        ->setQueryBuilder( // Ajout possible seulement pour des [objets] qui appartiennent au même propriétaire de l'[inventaire] que le [createur] de la [galerie]
            function (QueryBuilder $queryBuilder) {
            $currentTeam = $this->getContext()->getEntity()->getInstance(); // récupération de l'instance courante de [galerie]
            $creator = $currentTeam->getcreator();
            $memberId = $creator->getId();
            $queryBuilder->leftJoin('entity.album', 'i') // charge les seuls [objets] dont le 'owner' de l'[inventaire] est le [createur] de la galerie
                ->leftJoin('i.owner', 'm')
                ->andWhere('m.id = :member_id')
                ->setParameter('member_id', $memberId);
            return $queryBuilder;
            }
           ),
        ];
    }
}