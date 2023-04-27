<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Form\ProjectType;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    )
    {
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function userOwnedProjects()
    {
    }

    #[Route('/projects/create', name: 'app_projects_create')]
    public function createProject(
        #[CurrentUser] ?User $user,
        Request $request,
    ) {
        $project = $this->projectService->createProject($user);

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->projectService->handleSubmittedProject($project);

            if ($result === ProjectCreateStatusEnum::USER_CREATED) {
                return $this->render('project/project_with_user_created.html.twig');
            }


        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
