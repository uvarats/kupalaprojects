<?php

declare(strict_types=1);

namespace App\Feature\Participant\Service;

use App\Entity\Participant;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class ParticipantResolverService
{
    public function __construct(
        private Security $security,
    ) {}

    public function resolve(): ?Participant
    {
        if (!$this->security->isGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)) {
            return null;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('User is not user. Impossible case.');
        }

        return $user->getParticipant();
    }
}
