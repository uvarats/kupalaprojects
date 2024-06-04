<?php

declare(strict_types=1);

namespace App\Feature\Project\Security;

use App\Entity\Project;
use App\Entity\User;
use App\Security\Voter\FestivalVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Project>
 */
final class ProjectReportVoter extends Voter
{
    public const string PROJECT_REPORTING_ALLOWED = 'PROJECT_REPORTING_ALLOWED';

    public function __construct(
        private readonly Security $security,
    ) {}

    #[\Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::PROJECT_REPORTING_ALLOWED]) && $subject instanceof Project;
    }

    #[\Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::PROJECT_REPORTING_ALLOWED => $this->reportingAllowed($subject, $user),
            default => throw new \LogicException('Unknown attribute...'),
        };
    }

    private function reportingAllowed(Project $project): bool
    {
        $isProjectAuthor = $this->security->isGranted(ProjectVoter::IS_PROJECT_OWNER, $project);
        $isOrganizingCommitteeMember = $this->security->isGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $project->getFestival());

        return ($isProjectAuthor || $isOrganizingCommitteeMember) && $project->isReportingAllowed();
    }
}
