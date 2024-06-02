<?php

declare(strict_types=1);

namespace App\Feature\Import\Interface;

use App\Entity\Project;
use App\Entity\ProjectImport;

interface ProjectImportFactoryInterface
{
    public function makeParticipantsImport(Project $project, string $fileName): ProjectImport;
}
