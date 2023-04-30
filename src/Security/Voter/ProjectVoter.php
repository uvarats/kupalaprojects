<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks if user has rights to do actions with project
 */
class ProjectVoter extends Voter
{
    public const IS_PROJECT_OWNER = 'IS_PROJECT_OWNER';
    public const CAN_VOTE_FOR_PROJECT = 'CAN_VOTE_FOR_PROJECT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::IS_PROJECT_OWNER, self::CAN_VOTE_FOR_PROJECT]) &&
            $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }



        return false;
    }
}
