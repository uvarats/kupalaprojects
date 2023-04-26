<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\ProjectSubject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TypeError;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', ProjectAuthorType::class, [
                'label' => null,
            ])
            ->add('name', TextType::class)
            ->add('siteUrl', UrlType::class)
            ->add('creationYear', TextType::class)
            ->add('orientedOn', TextType::class)
            ->add('subjects', EntityType::class, [
                'class' => ProjectSubject::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('festival', EntityType::class, [
                'class' => Festival::class,
                'choice_label' => 'name',
            ])
            // TODO: https://symfony.com/doc/current/form/form_collections.html#allowing-new-tags-with-the-prototype
            ->add('awards', CollectionType::class, [
                'entry_type' => ProjectAwardType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
