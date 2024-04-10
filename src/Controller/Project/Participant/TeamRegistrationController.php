<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Dto\Form\Participant\TeamData;
use App\Entity\Project;
use App\Feature\Team\Service\TeamService;
use App\Form\Project\TeamDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id}/registration/team', name: 'app_project_registration_team')]
final class TeamRegistrationController extends AbstractController
{
    public function __construct(
        private TeamService $teamService,
    ) {}

    public function __invoke(Project $project, Request $request): Response
    {
        // change it do dtos, do not use entities in forms.
        $team = new TeamData();
        $team->setProject($project);
        $form = $this->createForm(TeamDataType::class, $team);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->render('project/team/registration.html.twig', [
                'team' => $team,
                'form' => $form->createView(),
            ]);
        }

        if ($form->isValid()) {

        }

        return $this->render('project/team/registration.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }
}
