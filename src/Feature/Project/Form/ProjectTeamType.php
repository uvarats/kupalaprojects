<?php

declare(strict_types=1);

namespace App\Feature\Project\Form;

use App\Entity\Project;
use App\Entity\Team;
use App\Feature\Participant\Service\ParticipantResolverService;
use App\Feature\Project\Dto\ProjectTeamData;
use App\Feature\Team\Repository\TeamRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProjectTeamType extends AbstractType
{
    public function __construct(
        private readonly ParticipantResolverService $participantResolverService,
        private readonly TeamRepository $teamRepository,
        private readonly TranslatorInterface $translator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $participant = $this->participantResolverService->resolve();

        if ($participant === null) {
            throw new \LogicException('Impossible, you must fill participant form to access this page. How did you do this?');
        }

        $choices = [null];
        $choices = array_merge($choices, $this->teamRepository
            ->findCreatedByParticipant($participant)
            ->toArray());

        $builder->add('team', EntityType::class, [
            'class' => Team::class,
            'choice_label' => function (?Team $team) use($options): string {
                if ($team === null) {
                    return 'Не выбрано';
                }

                $project = $options['project'];

                if ($project === null) {
                    return $team->getName();
                }

                assert($project instanceof Project);

                if ($project->hasRejectedTeam($team)) {
                    $rejectedLabel = '(' . $this->translator->trans('team.invite.status.rejected') . ')';
                    return $team->getName() . ' ' . $rejectedLabel;
                }

                return $team->getName();
            },
            'choice_attr' => function (?Team $choice) use ($options) {
                if ($choice === null) {
                    return [];
                }

                $project = $options['project'];
                if ($project === null) {
                    return [];
                }
                assert($project instanceof Project);
                if (!$project->hasRejectedTeam($choice)) {
                    return [];
                }

                return ['disabled' => 'disabled'];
            },
            'choices' => $choices,
            'label' => 'project.team.label',
            'help' => 'project.team.help',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectTeamData::class,
            'project' => null,
        ]);

        $resolver->setAllowedTypes('project', ['null', Project::class]);
    }
}
