<?php

declare(strict_types=1);

namespace App\Feature\Project\Collection;

use App\Collection\Collection;
use App\Entity\ProjectTeam;

/**
 * @extends Collection<ProjectTeam>
 */
final class ProjectTeamCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return ProjectTeam::class;
    }
}
