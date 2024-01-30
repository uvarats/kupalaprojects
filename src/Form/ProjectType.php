<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\Project;
use App\Repository\FestivalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('goal', TextareaType::class, [
                'attr' => [
                    'rows' => 4,
                ]
            ])
            ->add('siteUrl', UrlType::class)
            ->add('dates', EventDatesType::class)
            ->add('creationYear', NumberType::class)
            ->add('orientedOn', EducationSubGroupAutocompleteField::class)
            ->add('subjects', ProjectSubjectAutocompleteField::class)
            ->add('festival', EntityType::class, [
                'class' => Festival::class,
                'choice_label' => 'label',
                'query_builder' => static function (FestivalRepository $festivalRepository) {
                    return $festivalRepository->getOrderedBuilder();
                },
            ])
            ->add('awards', CollectionType::class, [
                'entry_type' => ProjectAwardType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false
            ])
            ->add('teamsAllowed', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
