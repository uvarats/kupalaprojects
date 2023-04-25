<?php

namespace App\Form;

use App\Entity\Interface\ProjectAuthorInterface;
use App\Entity\NullEntity\NullProjectAuthor;
use App\Entity\ProjectAuthor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TypeError;

class ProjectAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $author = $this->getProjectAuthor($options);

        $builder
            ->add('userEntity', UserType::class, [
                'disabled' => true,
            ])
            ->add('placeOfWork', TextType::class)
            ->add('occupation', TextType::class)
            ->add('reserveEmail', EmailType::class, [
                'required' => false,
            ])
        ;
    }

    private function getProjectAuthor(array $options): ProjectAuthorInterface
    {
        $author = $options['data'];

        if ($author !== null && !$author instanceof ProjectAuthor) {
            throw new TypeError();
        }

        if ($author === null) {
            return new NullProjectAuthor();
        }

        return $author;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectAuthor::class,
        ]);
    }
}
