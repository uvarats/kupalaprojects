<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks if user has rights to do actions with project
 */
class ProjectVoter extends Voter
{
    public const IS_PROJECT_OWNER = 'IS_PROJECT_OWNER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof \App\Entity\Project;
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
