<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'input_format' => 'd.m.Y',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateType::class];
    }
}
