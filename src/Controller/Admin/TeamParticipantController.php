<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\TeamParticipant;
use App\Enum\TeamParticipantRoleEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class TeamParticipantController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return TeamParticipant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Участник команды')
            ->setEntityLabelInPlural('Участники команд');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('team')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('participant')->setFormTypeOption('choice_label', 'fullName'),
            ChoiceField::new('role'),
            //TextField::new('role')->formatValue(fn (TeamParticipantRoleEnum $role) => $role->name),
        ];
    }
}
