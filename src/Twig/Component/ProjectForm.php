<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Dto\Form\Project\ProjectData;
use App\Form\ProjectType;
use App\Repository\Interface\FestivalRepositoryInterface;
use App\ValueObject\Entity\FestivalId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ProjectData $initialFormData = null;

    public function __construct(
        private readonly FestivalRepositoryInterface $festivalRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ProjectType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function updateDates(): void
    {
        $festivalId = FestivalId::fromString(
            id: $this->formValues['festival']
        );

        $festival = $this->festivalRepository->findById($festivalId);

        $startsAt = $festival->getStartsAt();
        $endsAt = $festival->getEndsAt();

        $this->formValues['dates']['startsAt'] = $startsAt->format('Y-m-d');
        $this->formValues['dates']['endsAt'] = $endsAt->format('Y-m-d');
    }
}
