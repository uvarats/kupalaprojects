<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Entity\Project;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ModerationController extends AbstractController
{
    #[Route('/project/{id}/participants/moderation', name: 'app_project_participant_moderation')]
    public function __invoke(
        Project $project,
        ProjectParticipantRepository $projectParticipantRepository,
    ): Response {
        $this->denyAccessUnlessGranted(ProjectVoter::IS_PROJECT_OWNER, $project);
        $participants = $projectParticipantRepository->findAllWithoutDecision($project);

        return $this->render('project/participant-moderation/index.html.twig', [
            'project' => $project,
            'participants' => $participants,
        ]);
    }
}
