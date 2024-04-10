<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\Form\Project\ProjectData;
use App\Entity\EventDates;
use App\Entity\Project;
use App\Enum\ProjectStateEnum;
use App\Enum\ProjectTransitionEnum;
use App\Service\Mail\ProjectMailerService;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ProjectService
{
    public function __construct(
        private WorkflowInterface $projectStateMachine,
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private ProjectMailerService $projectMailer,
    ) {}

    public function handleSubmittedProject(ProjectData $projectData): void
    {
        $user = $this->userService->getCurrentUser();

        if ($user === null) {
            throw new \LogicException('Expected, that user is authenticated for using this logic.');
        }

        $projectAuthor = $user->getProjectAuthor();

        $dates = $projectData->getDates();

        $project = Project::create(
            name: $projectData->getName(),
            siteUrl: $projectData->getSiteUrl(),
            creationYear: $projectData->getCreationYear(),
            dates: EventDates::make(
                startsAt: $dates->getStartsAt(),
                endsAt: $dates->getEndsAt(),
            ),
            festival: $projectData->getFestival(),
            author: $projectAuthor,
            state: ProjectStateEnum::UNDER_MODERATION,
            goal: $projectData->getGoal(),
            teamsAllowed: $projectData->isTeamsAllowed(),
        );

        $subjects = $projectData->getSubjects();
        foreach ($subjects as $subject) {
            $project->addSubject($subject);
        }

        $orientedOn = $projectData->getOrientedOn();
        foreach ($orientedOn as $oriented) {
            $project->addOrientedOn($oriented);
        }

        $awards = $projectData->getAwards();
        foreach ($awards as $award) {
            $project->addAward($award);
        }

        $this->saveAwards($projectData);

        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    private function saveAwards(ProjectData $projectData): void
    {
        $awards = $projectData->getAwards();

        foreach ($awards as $award) {
            $this->entityManager->persist($award);
        }

        //$this->entityManager->flush();
    }

    public function makeTransition(Project $project, ProjectTransitionEnum $transition): bool
    {
        return match ($transition) {
            default => $this->defaultTransition($project, $transition),
        };
    }

    private function defaultTransition(Project $project, ProjectTransitionEnum $transition): bool
    {
        if (!$this->projectStateMachine->can($project, $transition->value)) {
            return false;
        }

        $this->projectStateMachine->apply($project, $transition->value);
        $this->entityManager->flush();

        return true;
    }
}
