<?php

namespace App\Form\Project;

use App\Dto\Participant\TeamData;
use App\Entity\Project;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'team.name',
            ])
            ->add('teamCreator', ParticipantType::class)
            ->add('')
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'disabled' => true,
                'label' => 'project.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamData::class,
        ]);
    }
}
