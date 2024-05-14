<?php

declare(strict_types=1);

namespace App\Feature\Project\Service;

use App\Entity\Project;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TeamService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function submitTeam(Project $project, Team $team): void
    {
        $project->submitTeam($team);

        $this->em->flush();
    }
}
