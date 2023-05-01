<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Enum\ProjectTransitionEnum;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Security\Voter\FestivalVoter;
use App\Security\Voter\ProjectAuthorVoter;
use App\Service\Project\ProjectService;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\QueryString;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
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
        private readonly PaginatedFinderInterface $finder,
    ) {
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


    #[Route('/projects/{page}', name: 'app_projects')]
    public function allProjects(Request $request, int $page = 1): Response
    {
        $queryString = $request->query->get('query');

        /** @var TransformedFinder $finder */
        $finder = $this->finder;

        $pager = $this->finder->findPaginated($queryString);

        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);


        return $this->render('project/index.html.twig', [
            'projects' => $pager,
        ]);
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

    #[Route('/project/{id}/awards', name: 'app_project_awards')]
    public function awards(
        #[MapEntity(expr: 'repository.getProjectWithAwards(id)')]
        Project $project
    ): Response {
        return $this->render('project/project_awards.html.twig', [
            'project' => $project
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
