<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ProjectAuthor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class ProjectAuthorController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Авторы проектов')
            ->setEntityLabelInSingular('Автор проекта');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('userEntity'),
            TextField::new('placeOfWork'),
            TextField::new('occupation'),
            EmailField::new('reserveEmail'),
        ];
    }

    public static function getEntityFqcn(): string
    {
        return ProjectAuthor::class;
    }
}
