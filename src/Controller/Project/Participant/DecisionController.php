<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Entity\Participant;
use App\Entity\Project;
use App\Security\Voter\ProjectVoter;
use App\Service\Project\ParticipantService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class DecisionController extends AbstractController
{
    public function __construct(
        private readonly ParticipantService $participantService,
    ) {}

    #[Route('/project/{id}/participant/{participant_id}/{decision}', name: 'app_project_participant_decision')]
    public function __invoke(
        Project $project,
        #[MapEntity(mapping: ['participant_id' => 'id'])]
        Participant $participant,
        string $decision,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(ProjectVoter::IS_PROJECT_OWNER, $project);

        $this->participantService->makeParticipantDecision($participant, $decision);

        if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('project/participant-moderation/remove.stream.html.twig', [
                'participant_id' => (string)$participant->getId(),
            ]);
        }

        return $this->redirectToRoute('app_project_participant_moderation', [
            'id' => (string)$project->getId(),
        ]);
    }
}
