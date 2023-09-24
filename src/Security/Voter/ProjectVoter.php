<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks if user has rights to do actions with project
 */
final class ProjectVoter extends Voter
{
    public const IS_PROJECT_OWNER = 'IS_PROJECT_OWNER';
    public const CAN_VOTE_FOR_PROJECT = 'CAN_VOTE_FOR_PROJECT';
    public const CAN_VIEW_PARTICIPANTS = 'CAN_VIEW_PARTICIPANTS';
    public const CAN_VIEW_AWARDS = 'CAN_VIEW_AWARDS';

    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}


    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
                self::IS_PROJECT_OWNER,
                self::CAN_VOTE_FOR_PROJECT,
                self::CAN_VIEW_AWARDS,
                self::CAN_VIEW_PARTICIPANTS,
            ]) && $subject instanceof Project;
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
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::IS_PROJECT_OWNER => $this->isProjectOwner($user, $subject),
            self::CAN_VOTE_FOR_PROJECT => $this->canVoteForProject($user, $subject),
            self::CAN_VIEW_PARTICIPANTS => $this->canViewParticipants($user, $subject),
            self::CAN_VIEW_AWARDS => $this->canViewAwards($user, $subject),
        };
    }

    private function isProjectOwner(User $user, Project $project): bool
    {
        $author = $project->getAuthor();

        return $author->getUserEntity() === $user;
    }

    private function canVoteForProject(User $user, Project $project): bool
    {
        $festival = $project->getFestival();
        $jury = $festival->getJury();

        if (!$jury->contains($user)) {
            return false;
        }

        $projectAuthor = $project->getAuthor();
        $authorUser = $projectAuthor->getUserEntity();

        return $authorUser !== $user;
    }

    private function canViewAwards(User $user, Project $project): bool {}

    private function canViewParticipants(User $user, Project $project): bool {}
}
