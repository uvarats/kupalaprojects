<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Security\Voter\ProjectAuthorVoter;
use App\Service\Project\ProjectService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
        $query = $projectRepository->getUserProjectsQuery($user);

        $pager = new Pagerfanta(
            new QueryAdapter($query),
        );

        return $this->render('project/my_projects.html.twig', [
            'projects' => $pager,
        ]);
    }

    #[Route('/projects/create', name: 'app_projects_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createProject(
        Request $request,
    ): Response {
        if (!$this->isGranted(ProjectAuthorVoter::IS_PROJECT_AUTHOR)) {
            return $this->redirectToRoute('app_project_author_create');
        }

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
