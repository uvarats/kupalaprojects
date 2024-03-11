<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\FestivalMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

class FestivalMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'festival.mail.subject'
            ])
            ->add('content', CkeditorType::class, [
                'label' => 'festival.mail.content'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FestivalMail::class,
        ]);
    }
}
