<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\User;
use Cake\Chronos\Chronos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = Chronos::now();
        $year = $now->year;
        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $years[] = $year + $i;
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'festival.form.name',
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'festival.form.startsAt',
                'widget' => 'choice',
                'input' => 'datetime_immutable',
                'years' => $years,
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'festival.form.endsAt',
                'widget' => 'choice',
                'input' => 'datetime_immutable',
                'years' => $years,
            ])
            ->add('organizationCommittee', EntityType::class, [
                'class' => User::class,
                'label' => 'festival.form.organizationCommittee',
                'choice_label' => 'displayString',
                'multiple' => true,
            ])
            ->add('jury', EntityType::class, [
                'class' => User::class,
                'label' => 'festival.form.jury',
                'choice_label' => 'displayString',
                'multiple' => true,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'festival.form.is_active'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
