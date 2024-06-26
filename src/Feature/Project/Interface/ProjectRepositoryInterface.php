<?php

declare(strict_types=1);

namespace App\Feature\Project\Interface;

use App\Entity\Project;
use App\Feature\Project\ValueObject\ProjectId;

interface ProjectRepositoryInterface
{
    public function findOneById(ProjectId $projectId): ?Project;
}
