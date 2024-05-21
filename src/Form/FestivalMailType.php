<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Festival;
use App\Entity\FestivalMail;
use App\Entity\User;
use App\Feature\Festival\Dto\FestivalMailData;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

class FestivalMailType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $festival = $options['festival'];

        if ($festival === null) {
            throw new \DomainException('Something went wrong: festival is mandatory option for festival mail form.');
        }

        $recipients = [
            $this->translator->trans('mail.recipients.organization_committee') => $this->getOrganizationCommitteeMailOptions($festival),
            $this->translator->trans('mail.recipients.jury') => $this->getJuryMailOptions($festival),
            $this->translator->trans('mail.recipients.project_authors') => [
                'test2@mail.com' => 'Some user 2'
            ],
            $this->translator->trans('mail.recipients.participants') => [
                'test3@mail.com' => 'Some user 1'
            ], // including team owners (and maybe members),
        ];

        $builder
            ->add('subject', TextType::class, [
                'label' => 'festival.mail.subject'
            ])
            ->add('content', CkeditorType::class, [
                'label' => 'festival.mail.content'
            ])
            ->add('recipients', ChoiceType::class, [
                'multiple' => true,
                'choices' => $recipients,
            ])
        ;
    }

    private function getOrganizationCommitteeMailOptions(Festival $festival): array
    {
        $organizationCommittee = $festival->getOrganizationCommittee();

        $options = [];
        foreach ($organizationCommittee as $user) {
            $email = $user->getEmail();
            $label = $user->getDisplayString();

            $options[$label] = $email;
        }

        return $options;
    }

    private function getJuryMailOptions(Festival $festival): array
    {
        $jury = $festival->getJury();

        $options = [];
        foreach ($jury as $user) {
            $email = $user->getEmail();
            $label = $user->getDisplayString();

            $options[$label] = $email;
        }

        return $options;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FestivalMailData::class,
            'festival' => null,
        ]);

        $resolver->setAllowedTypes('festival', ['null', Festival::class]);
    }
}
