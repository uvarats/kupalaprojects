<?php

declare(strict_types=1);

namespace App\Feature\Team\Form;

use App\Feature\Team\Dto\InviteData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TeamInvitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('emails', TextType::class, [
            'autocomplete' => true,
            'tom_select_options' => [
                'create' => true,
                'createOnBlur' => true,
                'delimiter' => ',',
            ],
            'empty_data' => '',
            'label' => 'team.invite.emails',
        ]);

        $builder->get('emails')->addModelTransformer(
            new CallbackTransformer(
                function (?array $data): string {
                    //return $data;
                    if ($data === null) {
                        return '';
                    }

                    return implode(',', $data);
                },
                function (?string $reverseData): array {
                    if ($reverseData === null) {
                        return [];
                    }

                    return explode(',', $reverseData);
                },
            ),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InviteData::class,
        ]);
    }
}
