<?php

declare(strict_types=1);

namespace App\Feature\Project\Form;

use App\Entity\Team;
use App\Feature\Participant\Service\ParticipantResolverService;
use App\Feature\Project\Dto\ProjectTeamData;
use App\Feature\Team\Repository\TeamRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectTeamType extends AbstractType
{
    public function __construct(
        private readonly ParticipantResolverService $participantResolverService,
        private readonly TeamRepository $teamRepository,
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
            'choice_label' => static function (?Team $team): string {
                if ($team === null) {
                    return 'Не выбрано';
                }

                return $team->getName();
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
        ]);
    }
}
