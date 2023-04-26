<?php

namespace App\Form;

use App\Entity\User;
use App\FormConfig\FieldConfig;
use App\FormConfig\FormFieldsConfig;
use App\Service\Util\FormService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class UserType extends AbstractType
{
    public function __construct(private FormService $formService) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $this->onPreSetData(...));
    }

    private function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var User|null $data */
        $data = $event->getData();

        $config = new FormFieldsConfig();
        $config->setConfig('lastName', new FieldConfig())
            ->setConfig('firstName', new FieldConfig())
            ->setConfig('middleName', new FieldConfig([
                'required' => false,
            ]))
            ->setConfig('email', new FieldConfig());

        $config = $this->formService->disableFilledFields($data, $config);

        $form
            ->add('lastName', TextType::class, $config->getConfigArray('lastName'))
            ->add('firstName', TextType::class, $config->getConfigArray('firstName'))
            ->add('middleName', TextType::class, $config->getConfigArray('middleName'))
            ->add('email', EmailType::class, $config->getConfigArray('email'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
