<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Entity\Project;
use App\Entity\Team;
use App\Form\Project\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project/{id}/registration/team', name: 'app_project_registration_team')]
final class TeamRegistrationController extends AbstractController
{
    public function __invoke(Project $project, Request $request): Response
    {
        // change it do dtos, do not use entities in forms.
        $team = new Team();
        $team->setProject($project);
        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('project/team/registration.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }
}
