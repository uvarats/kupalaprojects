<?php

declare(strict_types=1);

namespace App\Feature\Project\Service;

use App\Entity\Project;
use App\Entity\ProjectTeam;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ProjectTeamService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function submitTeam(Project $project, Team $team): void
    {
        $project->submitTeam($team);

        $this->em->flush();
    }

    public function approve(ProjectTeam $team): void
    {
        $team->approve();

        $this->em->flush();
    }

    public function reject(ProjectTeam $team): void
    {
        $team->reject();

        $this->em->flush();
    }

    public function retractDecision(ProjectTeam $team): void
    {
        $team->retractDecision();

        $this->em->flush();
    }
}
