<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Entity\Project;
use App\Feature\Project\Security\ProjectParticipantVoter;
use App\Feature\Project\Service\ParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectSearchCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    public function __construct(
        private readonly ParticipantService $participantService,
    ) {}

    #[LiveAction]
    public function submitParticipant(#[CurrentParticipant] Participant $participant): void
    {
        if (!$this->isGranted(ProjectParticipantVoter::CAN_SUBMIT_FOR_PROJECT, $this->project)) {
            return;
        }

        $this->participantService->submitParticipant($this->project, $participant);
    }

    #[LiveAction]
    public function retractParticipantApplication(): void
    {
        dd('retracting');
    }

    #[LiveAction]
    public function submitTeam(): Response
    {
        return $this->redirectToRoute('app_project_registration_team', ['id' => $this->project->getId()]);
    }
}
