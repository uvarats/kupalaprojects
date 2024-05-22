<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Festival;
use App\Feature\Festival\Dto\FestivalMailData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FestivalMailType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    /**
     * Todo: maybe store recipients as relation and also store type to determine
     * It will require changing plain email to some kind of Dto.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $festival = $options['festival'];

        if ($festival === null) {
            throw new \DomainException('Something went wrong: festival is mandatory option for festival mail form.');
        }

        $recipients = [
            $this->translator->trans('mail.recipients.organization_committee') => $this->getOrganizationCommitteeMailOptions($festival),
            $this->translator->trans('mail.recipients.jury') => $this->getJuryMailOptions($festival),
            $this->translator->trans('mail.recipients.project_authors') => $this->getProjectAuthorsMailOptions($festival),
            $this->translator->trans('mail.recipients.participants') => $this->getParticipantsMailOptions($festival), // including team owners (and maybe members),
        ];

        $builder
            ->add('subject', TextType::class, [
                'label' => 'festival.mail.subject',
                'empty_data' => '',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'festival.mail.content',
                'empty_data' => '',
            ])
            ->add('recipients', ChoiceType::class, [
                'multiple' => true,
                //'expanded' => true,
                'choices' => $recipients,
                'help' => 'festival.mail.recipients_help',
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

    private function getProjectAuthorsMailOptions(Festival $festival): array
    {
        $projects = $festival->getProjects();

        $options = [];
        foreach ($projects as $project) {
            $projectAuthor = $project->getAuthor()->getUserEntity();
            $email = $projectAuthor->getEmail();
            $label = $projectAuthor->getDisplayString();

            $options[$label] = $email;
        }

        return $options;
    }

    private function getParticipantsMailOptions(Festival $festival): array
    {
        $projects = $festival->getProjects();

        $options = [];
        foreach ($projects as $project) {
            $participants = $project->getParticipants();
            foreach ($participants as $projectParticipant) {
                $participant = $projectParticipant->getParticipant();
                $email = $participant->getEmail();
                $label = $participant->getDisplayString();

                $options[$label] = $email;
            }

            if (!$project->isTeamsAllowed()) {
                return $options;
            }

            $teams = $project->getTeams();
            foreach ($teams as $projectTeam) {
                // todo: demeter law
                $teamCreator = $projectTeam->getTeam()->getCreator()->getParticipant();
                $email = $teamCreator->getEmail();
                $label = $teamCreator->getDisplayString();

                $options[$label] = $email;
            }
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
