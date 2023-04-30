<?php

namespace App\Controller\Admin;

use App\Entity\EducationSubGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EducationSubGroupController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EducationSubGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Образовательная подгруппа')
            ->setEntityLabelInPlural('Образовательные подгруппы');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('educationGroup')->setFormTypeOption('choice_label', 'name'),
        ];
    }

}
