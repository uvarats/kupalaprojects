<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Dto\Form\Participant\ParticipantData;
use App\Entity\Participant;
use App\Entity\Project;
use App\Validator\Constraint\UniqueInEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ParticipantDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'empty_data' => '',
            ])
            ->add('firstName', TextType::class, [
                'empty_data' => '',
            ])
            ->add('middleName', TextType::class, [
                'required' => false,
            ])
            ->add('educationEstablishment', TextType::class, [
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'help' => 'participant.email.help',
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipantData::class,
        ]);
    }
}
