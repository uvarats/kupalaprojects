<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Feature\Project\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id}/edit', name: 'app_projects_edit')]
final class EditAction extends AbstractController
{
    public function __invoke(Project $project, Request $request): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::IS_PROJECT_OWNER, $project);

        return $this->render('project/edit.html.twig', [
            'project' => $project,
        ]);
    }
}
