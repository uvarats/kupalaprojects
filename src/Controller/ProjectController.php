<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {
    }

    #[Route('/personal/projects', name: 'app_projects_personal')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function userOwnedProjects(
        #[CurrentUser] User $user,
        ProjectRepository $projectRepository
    ): Response {
        return $this->render('project/my_projects.html.twig', [

        ]);
    }

    #[Route('/projects/create', name: 'app_projects_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createProject(
        #[CurrentUser] User $user,
        Request $request,
    ): Response {


        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->handleSubmittedProject($project);

            return $this->redirectToRoute('app_projects_personal');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
