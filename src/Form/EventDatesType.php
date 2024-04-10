<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\EventDatesData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventDatesType extends AbstractType
{
    private const string STARTS_AT = 'startsAt';
    private const string ENDS_AT = 'endsAt';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $years = range((int)date('Y'), ((int)date('Y')) + 5);
        $builder
            ->add(self::STARTS_AT, DateType::class, [
                'years' => $years,
            ])
            ->add(self::ENDS_AT, DateType::class, [
                'years' => $years,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventDatesData::class,
        ]);
    }
}
