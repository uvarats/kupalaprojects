<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\ProjectParticipant;
use App\Feature\Project\Security\ProjectVoter;
use App\Feature\Project\Service\ParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ParticipantModerationCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public ProjectParticipant $participant;

    public function __construct(
        private readonly ParticipantService $participantService,
    ) {}

    #[LiveAction]
    public function approve(): void
    {
        $project = $this->participant->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->participantService->approve($this->participant);
    }

    #[LiveAction]
    public function reject(): void
    {
        $project = $this->participant->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->participantService->reject($this->participant);
    }

    #[LiveAction]
    public function retractDecision(): void
    {
        $project = $this->participant->getProject();
        if (!$this->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project)) {
            return;
        }

        $this->participantService->retractDecision($this->participant);
    }
}
