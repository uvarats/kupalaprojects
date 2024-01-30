<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Festival;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/manage')]
#[IsGranted('ROLE_ADMIN')]
final class FestivalController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Festival::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Фестиваль')
            ->setEntityLabelInPlural('Фестивали');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateField::new('startsAt'),
            DateField::new('endsAt'),
            AssociationField::new('organizationCommittee')->setFormTypeOption('choice_label', 'displayString'),
            AssociationField::new('jury')->setFormTypeOption('choice_label', 'displayString'),
            BooleanField::new('isActive'),
        ];
    }
}
