<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Enum\ProjectTransitionEnum;
use App\Security\Voter\FestivalVoter;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class FestivalProjectCard extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Project $project;

    #[LiveProp(writable: true, onUpdated: 'changeSelection')]
    public bool $selected = false;

    public function __construct(
        private readonly ProjectService $projectService,
    ) {}

    #[LiveAction]
    public function changeSelection(bool $previousValue): void
    {
        // firstly property value changed, then action triggered.
        // so, if selected === true, then we must trigger 'selectProject' event and vice versa
        if ($this->selected) {
            $this->emitUp('selectProject', [
                'project' => $this->project->getId(),
            ]);
        } else {
            $this->emitUp('unselectProject', [
                'project' => $this->project->getId(),
            ]);
        }
    }

    #[LiveAction]
    public function transition(#[LiveArg] ProjectTransitionEnum $transition): void
    {
        $project = $this->project;
        $festival = $project->getFestival();
        $this->denyAccessUnlessGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $festival);

        $this->projectService->makeTransition($project, $transition);
    }
}
