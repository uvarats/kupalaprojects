<?php

declare(strict_types=1);

namespace App\Feature\Project\Security;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\User;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Project>
 */
final class ProjectParticipantVoter extends Voter
{
    public const string IS_INDIVIDUAL_PARTICIPANT = 'IS_INDIVIDUAL_PARTICIPANT';
    public const string IS_APPROVED_PARTICIPANT = 'IS_APPROVED_PARTICIPANT';
    public const string IS_PENDING_PARTICIPANT = 'IS_PENDING_PARTICIPANT';
    public const string IS_REJECTED_PARTICIPANT = 'IS_REJECTED_PARTICIPANT';
    public const string CAN_SUBMIT_FOR_PROJECT = 'CAN_SUBMIT_FOR_PROJECT';
    public const string IS_SUBMITTED_FOR_PROJECT_THROUGH_TEAM = 'IS_SUBMITTED_FOR_PROJECT_THROUGH_TEAM';

    public function __construct(
        private readonly Security $security,
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::IS_APPROVED_PARTICIPANT,
            self::IS_PENDING_PARTICIPANT,
            self::IS_REJECTED_PARTICIPANT,
            self::CAN_SUBMIT_FOR_PROJECT,
            self::IS_INDIVIDUAL_PARTICIPANT,
            self::IS_SUBMITTED_FOR_PROJECT_THROUGH_TEAM,
        ]) && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (!$this->security->isGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)) {
            return false;
        }

        $participant = $user->getParticipant();
        assert($participant instanceof Participant);

        return match ($attribute) {
            self::IS_INDIVIDUAL_PARTICIPANT => $subject->hasIndividualParticipant($participant),
            self::IS_APPROVED_PARTICIPANT => $subject->hasApprovedParticipant($participant),
            self::IS_REJECTED_PARTICIPANT => $subject->hasRejectedParticipant($participant),
            self::IS_PENDING_PARTICIPANT => $subject->hasPendingParticipant($participant),
            self::CAN_SUBMIT_FOR_PROJECT => $subject->canAcceptParticipant($participant),
            self::IS_SUBMITTED_FOR_PROJECT_THROUGH_TEAM => $subject->hasTeamParticipant($participant),
        };
    }

}
