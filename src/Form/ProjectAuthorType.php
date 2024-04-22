<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ProjectAuthor;
use App\Service\Util\FormService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated
 */
final class ProjectAuthorType extends AbstractType
{
    public function __construct(private readonly FormService $formService) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userEntity', UserType::class, [
                'by_reference' => false,
            ])
            ->add('placeOfWork', TextType::class)
            ->add('occupation', TextType::class)
            ->add('reserveEmail', EmailType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectAuthor::class,
        ]);
    }
}
