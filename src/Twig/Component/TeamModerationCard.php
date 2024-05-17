<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\ProjectTeam;
use App\Feature\Project\Security\ProjectVoter;
use App\Feature\Project\Service\ProjectTeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamModerationCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public ProjectTeam $projectTeam;

    public function __construct(
        private readonly ProjectTeamService $teamService,
    ) {}

    #[LiveAction]
    public function approve(): void
    {
        $project = $this->projectTeam->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->teamService->approve($this->projectTeam);
    }

    #[LiveAction]
    public function reject(): void
    {
        $project = $this->projectTeam->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->teamService->reject($this->projectTeam);
    }

    #[LiveAction]
    public function retractDecision(): void
    {
        $project = $this->projectTeam->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->teamService->retractDecision($this->projectTeam);
    }
}
