<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Dto\Form\Project\ProjectData;
use App\Feature\Project\Form\ProjectType;
use App\Security\Voter\ProjectAuthorVoter;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/projects/create', name: 'app_projects_create')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CreateController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {}


    public function __invoke(
        Request $request,
    ): Response {
        if (!$this->isGranted(ProjectAuthorVoter::IS_PROJECT_AUTHOR)) {
            return $this->redirectToRoute('app_project_author_create');
        }

        $project = new ProjectData();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->handleSubmittedProject($project);

            return $this->redirectToRoute('app_projects_personal');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
