<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\ProjectAuthor;
use App\Entity\User;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ProjectService
{
    public function __construct(
        private WorkflowInterface $projectWorkflow
    ) {
    }

    public function createProjectAuthorFromUser(User $user): ProjectAuthor
    {
        $projectAuthor = new ProjectAuthor();
        $projectAuthor->setUserEntity($user);

        return $projectAuthor;
    }
}
