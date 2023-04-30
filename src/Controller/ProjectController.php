<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Enum\ProjectTransitionEnum;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Security\Voter\FestivalVoter;
use App\Security\Voter\ProjectAuthorVoter;
use App\Service\Project\ProjectService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    #[Route('/personal/projects/{page}', name: 'app_projects_personal')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function userOwnedProjects(
        #[CurrentUser] User $user,
        ProjectRepository $projectRepository,
        int $page = 1,
    ): Response {
        $query = $projectRepository->getUserProjectsQuery($user);

        $pager = new Pagerfanta(
            new QueryAdapter($query),
        );
        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);

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

    #[Route(
        path: '/project/{id}/transition/{transition}',
        name: 'app_project_transition',
        requirements: [
            'transition' => new EnumRequirement(
                ProjectTransitionEnum::class
            )
        ])
    ]
    public function makeTransition(
        #[MapEntity(expr: 'repository.eagerLoad(id)')]
        Project $project,
        ProjectTransitionEnum $transition,
        Request $request
    ): Response {
        $festival = $project->getFestival();
        $this->denyAccessUnlessGranted(
            FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER,
            $festival
        );

        $this->projectService->makeTransition($project, $transition);

        return $this->projectTransitionResponse($project, $request);
    }

    private function projectTransitionResponse(
        Project $project,
        Request $request
    ): Response {
        if ($request->headers->get('Turbo-Frame') !== null) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('festival/_project_card_stream.html.twig', [
                'project' => $project
            ]);
        }

        $festival = $project->getFestival();
        return $this->redirectToRoute('app_festival_projects', [
            'id' => (string)$festival->getId()
        ]);
    }
}
