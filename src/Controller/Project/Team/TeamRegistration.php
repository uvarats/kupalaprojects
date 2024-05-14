<?php

declare(strict_types=1);

namespace App\Controller\Project\Team;

use App\Entity\Project;
use App\Feature\Project\Security\ProjectParticipantVoter;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/project/{id}/team/registration', name: 'app_project_registration_team')]
#[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
final class TeamRegistration extends AbstractController
{
    public function __invoke(Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectParticipantVoter::NOT_SUBMITTED_FOR_PROJECT, $project);

        return $this->render('project/team/registration.html.twig', ['project' => $project]);
    }
}
