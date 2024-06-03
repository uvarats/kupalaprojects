<?php

declare(strict_types=1);

namespace App\Feature\Import\Collection;

use App\Collection\Collection;
use App\Entity\ProjectImport;

/**
 * @extends Collection<ProjectImport>
 */
final class ProjectImportCollection extends Collection
{

    public function getType(): string
    {
        return ProjectImport::class;
    }
}
