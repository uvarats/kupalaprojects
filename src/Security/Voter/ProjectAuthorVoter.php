<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks if user has linked project author entity
 * @extends Voter<string, Project>
 */
class ProjectAuthorVoter extends Voter
{
    public const string IS_PROJECT_AUTHOR = 'IS_PROJECT_AUTHOR_USER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::IS_PROJECT_AUTHOR;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        assert($user instanceof User);

        return $user->getProjectAuthor() !== null;
    }
}
