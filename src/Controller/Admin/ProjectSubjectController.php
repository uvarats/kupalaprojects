<?php

namespace App\Controller\Admin;

use App\Entity\ProjectSubject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

final class ProjectSubjectController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectSubject::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Предмет проектов')
            ->setEntityLabelInPlural('Предметы проектов')
            ->setEntityPermission('ROLE_ADMIN')
        ;
    }
}
