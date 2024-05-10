<?php

declare(strict_types=1);

namespace App\Feature\Project\Security;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Project>
 */
final class ProjectParticipantVoter extends Voter
{
    public const string IS_ACCEPTED_PARTICIPANT = 'IS_ACCEPTED_PARTICIPANT';
    public const string IS_PENDING_PARTICIPANT = 'IS_PENDING_PARTICIPANT';
    public const string IS_REJECTED_PARTICIPANT = 'IS_REJECTED_PARTICIPANT';

    public function __construct(
        private Security $security,
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::IS_ACCEPTED_PARTICIPANT,
            self::IS_PENDING_PARTICIPANT,
            self::IS_REJECTED_PARTICIPANT,
        ]) && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (!$this->security->isGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)) {
            return false;
        }

        $participant = $user->getParticipant();
        assert($participant instanceof Participant);

        if (!$subject->hasParticipant($participant)) {
            return false;
        }

        return match ($attribute) {
            self::IS_ACCEPTED_PARTICIPANT => throw new \LogicException(),
            self::IS_REJECTED_PARTICIPANT => throw new \LogicException(),
            self::IS_PENDING_PARTICIPANT => throw new \LogicException(),
        };
    }

    private function isPendingParticipant(Project $project): bool
    {
        // todo
    }
}
