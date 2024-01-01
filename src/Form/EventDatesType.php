<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EventDates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventDatesType extends AbstractType implements DataMapperInterface
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
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventDates::class,
        ]);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        if ($viewData === null) {
            return;
        }

        if (!$viewData instanceof EventDates) {
            throw new UnexpectedTypeException($viewData, EventDates::class);
        }

        /** @var array<string, FormInterface> $forms */
        $forms = iterator_to_array($forms);

        $startsAt = $viewData->getStartsAt();
        $forms[self::STARTS_AT]->setData($startsAt);

        $endsAt = $viewData->getEndsAt();
        $forms[self::ENDS_AT]->setData($endsAt);
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        /** @var array<string, FormInterface> $forms */
        $forms = iterator_to_array($forms);

        $startsAt = $forms[self::STARTS_AT]->getData();
        $endsAt = $forms[self::ENDS_AT]->getData();
        $viewData = EventDates::make($startsAt, $endsAt);
    }
}
