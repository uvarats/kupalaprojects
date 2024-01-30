<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Project;
use App\Entity\ProjectStateLog;
use App\Enum\ProjectStateEnum;
use App\Service\User\UserResolverInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;

final readonly class ProjectUpdateListener
{
    private const string STATE = 'state';

    public function __construct(
        private UserResolverInterface $userResolver,
    ) {}

    public function onFlush(OnFlushEventArgs $args): void
    {
        $objectManager = $args->getObjectManager();
        $unitOfWork = $objectManager->getUnitOfWork();

        $updates = $unitOfWork->getScheduledEntityUpdates();
        foreach ($updates as $scheduledForUpdate) {
            if (!$scheduledForUpdate instanceof Project) {
                continue;
            }

            $changeSet = $unitOfWork->getEntityChangeSet($scheduledForUpdate);

            if (!isset($changeSet[self::STATE])) {
                continue;
            }

            [$oldValue, $newValue] = $changeSet[self::STATE];

            $oldState = ProjectStateEnum::from($oldValue);
            $newState = ProjectStateEnum::from($newValue);

            $logEntry = ProjectStateLog::make(
                project: $scheduledForUpdate,
                fromState: $oldState,
                toState: $newState,
                performedBy: $this->userResolver->getCurrentUser(),
            );
            $objectManager->persist($logEntry);

            $metadata = $objectManager->getClassMetadata(ProjectStateLog::class);
            $unitOfWork->computeChangeSet($metadata, $logEntry);
        }
    }
}
