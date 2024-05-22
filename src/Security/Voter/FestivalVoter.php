<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Festival;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Festival>
 */
class FestivalVoter extends Voter
{
    public const string IS_JURY_MEMBER = 'IS_JURY_MEMBER';
    public const string IS_ORGANIZATION_COMMITTEE_MEMBER = 'IS_ORGANIZATION_COMMITTEE_MEMBER';
    public const string IS_FESTIVAL_STAFF = 'IS_FESTIVAL_STAFF';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array(
            $attribute,
            [
                self::IS_JURY_MEMBER,
                self::IS_ORGANIZATION_COMMITTEE_MEMBER,
                self::IS_FESTIVAL_STAFF,
            ],
        )
            && $subject instanceof Festival;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::IS_JURY_MEMBER => $subject->isJuryMember($user),
            self::IS_ORGANIZATION_COMMITTEE_MEMBER => $subject->isOrganizingCommitteeMember($user),
            self::IS_FESTIVAL_STAFF => $subject->isFestivalStaff($user),
            default => false,
        };
    }

}
