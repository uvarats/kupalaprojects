<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, null>
 */
final class ParticipantVoter extends Voter
{
    public const string HAS_PARTICIPANT_DATA = 'HAS_PARTICIPANT_DATA';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::HAS_PARTICIPANT_DATA;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->getParticipant() !== null;
    }
}
