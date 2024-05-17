<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Project\Collection\ProjectTeamCollection;
use App\Feature\Project\Repository\ProjectTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class TeamModeration extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    public function __construct(
        private readonly ProjectTeamRepository $projectTeamRepository,
    ) {}

    #[ExposeInTemplate]
    public function getTeams(): ProjectTeamCollection
    {
        return $this->projectTeamRepository->findByProject($this->project);
    }
}
