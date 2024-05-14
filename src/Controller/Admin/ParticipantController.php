<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Participant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

final class ParticipantController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Участник')
            ->setEntityLabelInPlural('Участники');
    }
}
