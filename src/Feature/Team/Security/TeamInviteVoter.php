<?php

declare(strict_types=1);

namespace App\Feature\Team\Security;

use App\Entity\TeamInvite;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, TeamInvite>
 */
final class TeamInviteVoter extends Voter
{
    public const string IS_ISSUER = 'IS_INVITE_ISSUER';
    public const string IS_RECIPIENT = 'IS_INVITE_RECIPIENT';

    public function __construct(
        private readonly Security $security,
    ) {}

    #[\Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::IS_ISSUER, self::IS_RECIPIENT]) && $subject instanceof TeamInvite;
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

        return match($attribute) {
            self::IS_RECIPIENT => $subject->isRecipient($participant),
            self::IS_ISSUER => $subject->isIssuer($participant),
        };
    }
}
