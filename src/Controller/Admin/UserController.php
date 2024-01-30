<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

final class UserController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Пользователь')
            ->setEntityLabelInPlural('Пользователи');
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }
}
