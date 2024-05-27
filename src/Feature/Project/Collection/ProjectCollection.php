<?php

declare(strict_types=1);

namespace App\Feature\Project\Collection;

use App\Collection\Collection;
use App\Entity\Project;

/**
 * @extends Collection<Project>
 *
 * @method Project offsetGet(mixed $offset)
 */
final class ProjectCollection extends Collection
{
    public function getType(): string
    {
        return Project::class;
    }
}
