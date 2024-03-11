<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EducationSubGroup;
use App\Repository\EducationSubGroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class EducationSubGroupAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => EducationSubGroup::class,
            'placeholder' => 'Выберите группы участников',
            'choice_label' => 'name',
            'multiple' => true,
            'query_builder' => function (EducationSubGroupRepository $educationSubGroupRepository) {
                return $educationSubGroupRepository->createQueryBuilder('educationSubGroup');
            },
            'no_more_results_text' => 'Больше результатов нет',
            'no_results_found_text' => 'Групп не найдено. Возможно, это ошибка.'
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
