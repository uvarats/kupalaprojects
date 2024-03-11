<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ProjectSubject;
use App\Repository\ProjectSubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ProjectSubjectAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => ProjectSubject::class,
            'placeholder' => 'Выбор предметов',
            'choice_label' => 'name',
            'multiple' => true,
            'query_builder' => function (ProjectSubjectRepository $projectSubjectRepository) {
                return $projectSubjectRepository->createQueryBuilder('projectSubject');
            },
            'no_more_results_text' => 'Больше результатов нет',
            'no_results_found_text' => 'Предметов не найдено. Возможно это ошибка.'
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
