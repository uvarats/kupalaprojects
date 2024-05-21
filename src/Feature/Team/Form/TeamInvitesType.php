<?php

declare(strict_types=1);

namespace App\Feature\Team\Form;

use App\Feature\Core\Form\CommaSeparatedTransformer;
use App\Feature\Team\Dto\InviteData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TeamInvitesType extends AbstractType
{
    public function __construct(
        private readonly CommaSeparatedTransformer $commaSeparatedTransformer,
    ) {}

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
            $this->commaSeparatedTransformer,
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InviteData::class,
        ]);
    }
}
