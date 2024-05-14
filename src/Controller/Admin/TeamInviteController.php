<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\TeamInvite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

final class TeamInviteController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return TeamInvite::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Приглашение в команду')
            ->setEntityLabelInPlural('Приглашения в команды');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('team')->setSortProperty('name')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('issuer', 'Invite Issuer')->setFormTypeOption('choice_label', 'displayString'),
            AssociationField::new('recipient')->setFormTypeOption('choice_label', 'displayString'),
            ChoiceField::new('status')->setDisabled(),
            DateTimeField::new('issuedAt'),
            DateTimeField::new('updatedAt'),
        ];
    }
}
