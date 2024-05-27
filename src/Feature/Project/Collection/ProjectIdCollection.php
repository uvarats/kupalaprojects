<?php

declare(strict_types=1);

namespace App\Feature\Project\Collection;

use App\Collection\Collection;
use App\Feature\Project\ValueObject\ProjectId;

/**
 * @extends Collection<ProjectId>
 */
final class ProjectIdCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return ProjectId::class;
    }
}
