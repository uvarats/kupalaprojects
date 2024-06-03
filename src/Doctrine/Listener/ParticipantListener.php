<?php

declare(strict_types=1);

namespace App\Doctrine\Listener;

use App\Entity\Participant;
use App\Feature\Account\Repository\UserRepository;
use App\Feature\Participant\Collection\ParticipantCollection;
use Doctrine\ORM\Event\OnFlushEventArgs;

final readonly class ParticipantListener
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function onFlush(OnFlushEventArgs $args): void
    {
        $om = $args->getObjectManager();
        $uow = $om->getUnitOfWork();

        $participantsWithoutUser = new ParticipantCollection();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Participant || $entity->getAccount() !== null) {
                continue;
            }

            $email = $entity->getEmail();
            $participantsWithoutUser[$email] = $entity;
        }

        if ($participantsWithoutUser->isEmpty()) {
            return;
        }

        $emails = array_keys($participantsWithoutUser->toArray());
        $users = $this->userRepository->findAllUsingEmailArray($emails);

        $classMetadata = $om->getClassMetadata(Participant::class);
        foreach ($users as $user) {
            $userEmail = $user->getEmail();
            $participant = $participantsWithoutUser[$userEmail];

            $participant->setAccount($user);
            $uow->recomputeSingleEntityChangeSet($classMetadata, $participant);
        }
    }
}
