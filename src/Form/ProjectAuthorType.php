<?php

namespace App\Form;

use App\Entity\Interface\ProjectAuthorInterface;
use App\Entity\ProjectAuthor;
use App\FormConfig\FieldConfig;
use App\FormConfig\FormFieldsConfig;
use App\Service\Util\FormService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectAuthorType extends AbstractType
{
    public function __construct(private readonly FormService $formService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $this->onPreSetData(...));
    }

    private function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ProjectAuthorInterface|null $data */
        $data = $event->getData();

        $config = new FormFieldsConfig();
        $config->setConfig('placeOfWork', new FieldConfig())
            ->setConfig('occupation', new FieldConfig())
            ->setConfig(
                'reserveEmail',
                new FieldConfig([
                    'required' => false,
                ])
            );

        $config = $this->formService->disableFilledFields($data, $config);


        $form->add('userEntity', UserType::class)
            ->add('placeOfWork', TextType::class, $config->getConfigArray('placeOfWork'))
            ->add('occupation', TextType::class, $config->getConfigArray('occupation'))
            ->add('reserveEmail', EmailType::class, $config->getConfigArray('reserveEmail'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectAuthor::class,
        ]);
    }
}
