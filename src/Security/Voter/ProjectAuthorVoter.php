<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectAuthorVoter extends Voter
{
    public const JURY_MEMBER = 'project_author';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::JURY_MEMBER &&
            $subject instanceof ProjectAuthor;
    }

    /**
     * @param string $attribute
     * @param Project $subject
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

        assert($user instanceof User);

        $userProjectAuthor = $user->getProjectAuthor();
        if ($userProjectAuthor === null) {
            return false;
        }

        return $userProjectAuthor === $subject->getAuthor();
    }
}
