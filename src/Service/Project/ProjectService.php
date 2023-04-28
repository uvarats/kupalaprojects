<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\NewUserProject;
use App\Dto\ProjectCreateResult;
use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\ProjectAward;
use App\Entity\User;
use App\Enum\ProjectCreateStatusEnum;
use App\Enum\ProjectStateEnum;
use App\Service\Mail\ProjectMailerService;
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

    public function handleSubmittedProject(Project $project): void
    {
        $user = $this->userService->getCurrentUser();
        $projectAuthor = $user->getProjectAuthor();

        $project
            ->setAuthor($projectAuthor)
            ->setState(ProjectStateEnum::UNDER_MODERATION->value);

        $this->saveAwards($project);

        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    private function saveAwards(Project $project): void
    {
        $awards = $project->getAwards();

        /** @var ProjectAward $award */
        foreach ($awards as $award) {
            $this->entityManager->persist($award);
        }

        //$this->entityManager->flush();
    }
}
