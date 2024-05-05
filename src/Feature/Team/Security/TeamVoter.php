<?php

declare(strict_types=1);

namespace App\Feature\Team\Security;

use App\Entity\Team;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Team>
 */
final class TeamVoter extends Voter
{
    public const string IS_TEAM_OWNER = 'IS_TEAM_OWNER';

    public function __construct(
        private readonly Security $security,
    ) {}

    #[\Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::IS_TEAM_OWNER]) && $subject instanceof Team;
    }

    #[\Override]
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

        return $subject->isCreator($participant);
    }
}
