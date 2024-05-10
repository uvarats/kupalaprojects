<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Entity\Project;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use App\Service\Project\ParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly ParticipantService $participantService,
    ) {}

    #[Route('/project/{id}/participant/submit', name: 'app_project_participant_submit')]
    #[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
    public function __invoke(Project $project, #[CurrentUser] User $user, Request $request): Response
    {
        $participant = $user->getParticipant();

        assert($participant !== null);

        $this->participantService->submitParticipant($project, $participant);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
