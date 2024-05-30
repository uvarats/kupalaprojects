<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Dto\Form\Project\ProjectData;
use App\Entity\Festival;
use App\Entity\Project;
use App\Feature\Project\Form\ProjectType;
use App\Repository\Interface\FestivalRepositoryInterface;
use App\Service\Project\ProjectService;
use App\ValueObject\Entity\FestivalId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'projectEntity')]
    public ?Project $project = null;

    public function __construct(
        private readonly FestivalRepositoryInterface $festivalRepository,
        private readonly ProjectService $projectService,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        $initialData = new ProjectData();

        if ($this->project !== null) {
            $initialData = ProjectData::fromProject($this->project);
        }

        return $this->createForm(
            ProjectType::class,
            $initialData,
            [
                'is_editing' => $this->project !== null,
                'festival' => $this->resolveFestival(),
            ]
        );
    }

    private function resolveFestival(): ?Festival
    {
        if ($this->project !== null) {
            return $this->project->getFestival();
        }

        $formFestival = $this->formValues['festival'] ?? null;

        if ($formFestival === null) {
            return null;
        }

        $festivalId = FestivalId::fromString($formFestival);

        return $this->festivalRepository->findById($festivalId);
    }

    #[LiveAction]
    public function updateDates(): void
    {
        $festivalId = FestivalId::fromString(
            id: $this->formValues['festival'],
        );

        $festival = $this->festivalRepository->findById($festivalId);

        $startsAt = $festival->getStartsAt();
        $endsAt = $festival->getEndsAt();

        $this->formValues['dates']['startsAt'] = $startsAt->format('Y-m-d');
        $this->formValues['dates']['endsAt'] = $endsAt->format('Y-m-d');
    }

    #[LiveAction]
    public function addAward(): void
    {
        $this->formValues['awards'][] = [];
    }

    #[LiveAction]
    public function removeAward(#[LiveArg] int $index): void
    {
        unset($this->formValues['awards'][$index]);
    }

    #[LiveAction]
    public function save(): Response
    {
        $this->submitForm();

        $projectData = $this->getForm()->getData();
        if (!$this->isEditing()) {
            $this->projectService->handleSubmittedProject($projectData);

            return $this->redirectToRoute('app_projects_personal');
        }

        $this->projectService->update($this->project, $projectData);

        return $this->redirectToRoute('app_projects_personal');
    }

    private function isEditing(): bool
    {
        return $this->project !== null;
    }
}
