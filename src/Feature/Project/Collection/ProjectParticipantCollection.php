<?php

declare(strict_types=1);

namespace App\Feature\Project\Collection;

use App\Collection\Collection;
use App\Entity\ProjectParticipant;

/**
 * @extends Collection<ProjectParticipant>
 */
final class ProjectParticipantCollection extends Collection
{
    public function getType(): string
    {
        return ProjectParticipant::class;
    }
}
