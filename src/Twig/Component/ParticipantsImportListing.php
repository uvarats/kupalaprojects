<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Import\Collection\ProjectImportCollection;
use App\Feature\Import\Repository\ProjectImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class ParticipantsImportListing extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    public function __construct(
        private readonly ProjectImportRepository $importRepository,
    ) {}

    #[ExposeInTemplate]
    public function getImports(): ProjectImportCollection
    {
        return $this->importRepository->findParticipantImportsForProject($this->project);
    }
}
