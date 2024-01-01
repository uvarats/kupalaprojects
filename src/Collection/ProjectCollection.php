<?php

declare(strict_types=1);

namespace App\Collection;

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
