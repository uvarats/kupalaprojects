<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ProjectService
{
    public function __construct(
        private WorkflowInterface $projectWorkflow,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function createProject(?User $user): Project
    {
        if ($user === null) {
            $user = new User();
        }

        $projectAuthor = $user->getProjectAuthor();
        if ($projectAuthor === null) {
            $projectAuthor = $this->createProjectAuthorFromUser($user);
        }

        $project = new Project();
        $project->setAuthor($projectAuthor);

        return $project;
    }

    public function createProjectAuthorFromUser(User $user): ProjectAuthor
    {
        $projectAuthor = new ProjectAuthor();
        $projectAuthor->setUserEntity($user);

        return $projectAuthor;
    }
}
