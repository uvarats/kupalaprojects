<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Dto\ProjectQuery;
use App\Entity\Festival;
use App\Feature\Festival\Repository\FestivalRepository;
use App\Form\EducationSubGroupAutocompleteField;
use App\Form\ProjectSubjectAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProjectQueryType extends AbstractType
{
    public function __construct(
        private readonly FestivalRepository $festivalRepository,
        private readonly TranslatorInterface $translator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $festivals = $this->festivalRepository
            ->findActive()
            ->toArray();
        $festivals = array_merge([null], $festivals);

        $builder->add('query', TextType::class, [
            'label' => 'search.query',
            'required' => false,
            'empty_data' => null,
            'attr' => [
                'placeholder' => 'search.placeholder'
            ],
            'help' => 'project.query.help'
        ])
            ->add('dateFrom', DateType::class, [
                'label' => 'search.dateFrom',
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'search.dateTo',
                'required' => false,
            ])
            ->add('subjects', ProjectSubjectAutocompleteField::class, [
                'required' => false,
                'empty_data' => [],
            ])
            ->add('orientedOn', EducationSubGroupAutocompleteField::class, [
                'required' => false,
                'empty_data' => [],
            ])
            ->add('festival', EntityType::class, [
                'class' => Festival::class,
                'choices' => $festivals,
                'required' => false,
                'empty_data' => null,
                'choice_label' => function (?Festival $festival) {
                    return $festival?->getName() ?? $this->translator->trans('search.empty_festival');
                },
                'placeholder' => 'search.empty_festival',
                'label' => 'Festival',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectQuery::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
