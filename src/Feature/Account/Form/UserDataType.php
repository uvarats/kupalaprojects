<?php

declare(strict_types=1);

namespace App\Feature\Account\Form;

use App\Feature\Account\Dto\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserDataType extends AbstractType
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
            ->add('email', EmailType::class, [
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserData::class,
        ]);
    }
}
