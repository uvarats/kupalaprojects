<?php

namespace App\Security\Voter;

use App\Entity\Festival;
use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FestivalVoter extends Voter
{
    public const IS_JURY_MEMBER = 'IS_JURY_MEMBER';
    public const IS_ORGANIZATION_COMMITTEE_MEMBER = 'IS_ORGANIZATION_COMMITTEE_MEMBER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array(
            $attribute,
            [
                    self::IS_JURY_MEMBER,
                    self::IS_ORGANIZATION_COMMITTEE_MEMBER
                ]
        )
            && $subject instanceof Festival;
    }

    /**
     * @param string $attribute
     * @param Festival $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $festivalRelatedUsers = match ($attribute) {
            self::IS_JURY_MEMBER => $subject->getJury(),
            self::IS_ORGANIZATION_COMMITTEE_MEMBER => $subject->getOrganizationCommittee(),
            default => null,
        };

        if ($festivalRelatedUsers === null) {
            return false;
        }

        return $festivalRelatedUsers->contains($user);
    }

}
