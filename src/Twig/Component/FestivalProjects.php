<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Festival;
use App\Entity\Project;
use App\Enum\ProjectTransitionEnum;
use App\Feature\Project\Collection\ProjectIdCollection;
use App\Feature\Project\Repository\ProjectRepository;
use App\Feature\Project\ValueObject\ProjectId;
use App\Security\Voter\FestivalVoter;
use App\Service\Project\ProjectService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class FestivalProjects extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public int $page = 1;

    #[LiveProp]
    public int $maxPerPage = 50;

    #[LiveProp]
    public Festival $festival;

    /**
     * @var string[] $selectedProjects
     */
    #[LiveProp(writable: true, hydrateWith: 'hydrateSelectedProjects', dehydrateWith: 'dehydrateSelectedProjects')]
    public array $selectedProjects = [];

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ProjectService $projectService,
    ) {}

    #[LiveAction]
    public function massTransition(#[LiveArg] ProjectTransitionEnum $transition): void
    {
        $this->denyAccessUnlessGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $this->festival);

        $identifiers = new ProjectIdCollection();
        foreach ($this->selectedProjects as $selectedProjectId) {
            $identifiers[] = ProjectId::fromString($selectedProjectId);
        }

        $projects = $this->projectRepository->findAllById($identifiers);

        foreach ($projects as $project) {
            $this->projectService->makeTransition($project, $transition);
        }
    }

    #[LiveAction]
    public function unselectAll(): void
    {
        $this->selectedProjects = [];
    }

    #[LiveListener('selectProject')]
    public function selectProject(#[LiveArg] Project $project): void
    {
        $id = (string)$project->getId();
        $this->selectedProjects[] = $id;
    }

    #[LiveListener('unselectProject')]
    public function unselectProject(#[LiveArg] Project $project): void
    {
        $id = (string)$project->getId();

        $key = array_search($id, $this->selectedProjects);

        if ($key === null) {
            dd('bug');
            return;
        }

        unset($this->selectedProjects[$key]);
    }

    #[ExposeInTemplate]
    public function getProjectsPaginator(): Pagerfanta
    {
        $festival = $this->festival;
        $projectsQuery = $this->projectRepository->getFestivalProjectsQuery($festival);

        $pager = new Pagerfanta(
            new QueryAdapter($projectsQuery),
        );
        $pager->setMaxPerPage($this->maxPerPage)
            ->setCurrentPage($this->page);

        return $pager;
    }

    public function isSelected(Project $project): bool
    {
        $id = (string)$project->getId();
        return in_array($id, $this->selectedProjects);
    }

    public function dehydrateSelectedProjects(array $data): array
    {
        return $data;
    }

    public function hydrateSelectedProjects(array $data): array
    {
        return $data;
    }
}
