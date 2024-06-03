<?php

declare(strict_types=1);

namespace App\Feature\Import\Factory;

use App\Entity\Project;
use App\Entity\ProjectImport;
use App\Feature\Import\Enum\ImportTypeEnum;
use App\Feature\Import\Interface\ProjectImportFactoryInterface;

final readonly class ProjectImportFactory implements ProjectImportFactoryInterface
{

    public function makeParticipantsImport(Project $project, string $fileName): ProjectImport
    {
        return new ProjectImport(
            ImportTypeEnum::PARTICIPANT,
            $fileName,
            $project,
        );
    }
}
