<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\NewUserProject;
use App\Dto\ProjectCreateResult;
use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ProjectService
{
    public function __construct(
        private WorkflowInterface $projectWorkflow,
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private ProjectMailerService $projectMailer,
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

    public function handleSubmittedProject(Project $project): ProjectCreateStatusEnum
    {
        $result = $this->saveProject($project);

        if ($result->status === ProjectCreateStatusEnum::USER_CREATED) {
            $data = new NewUserProject(
                project: $project,
                userPassword: $result->password,
            );

            $this->projectMailer->sendNewUserEmail($data);
        }

        return $result->status;
    }

    private function saveProject(Project $project): ProjectCreateResult
    {
        $status = ProjectCreateStatusEnum::ONLY_PROJECT_CREATED;
        $userPassword = $this->userService->handleProjectUser($project);

        if ($userPassword !== null) {
            $status = ProjectCreateStatusEnum::USER_CREATED;
        }

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return new ProjectCreateResult(
            status: $status,
            password: $userPassword,
        );
    }
}
