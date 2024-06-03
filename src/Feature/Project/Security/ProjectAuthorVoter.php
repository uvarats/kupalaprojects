<?php

declare(strict_types=1);

namespace App\Feature\Project\Security;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks if user has linked project author entity
 * @extends Voter<string, Project|null>
 */
class ProjectAuthorVoter extends Voter
{
    public const string HAS_PROJECT_AUTHOR_DATA = 'IS_PROJECT_AUTHOR_USER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::HAS_PROJECT_AUTHOR_DATA,
        ]);
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
