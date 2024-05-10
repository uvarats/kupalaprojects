<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\Entity\Team;
use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Participant\ValueObject\ParticipantId;
use App\Feature\Team\ValueObject\TeamId;

final readonly class IssueInvitesRequest
{
    private function __construct(
        private EmailCollection $emails,
        private TeamId $teamId,
        private ParticipantId $issuerId,
    ) {}

    public static function make(EmailCollection $emails, TeamId $teamId, ParticipantId $issuerId): IssueInvitesRequest
    {
        return new self($emails, $teamId, $issuerId);
    }

    public function getEmails(): EmailCollection
    {
        return $this->emails;
    }

    public function getTeamId(): TeamId
    {
        return $this->teamId;
    }

    public function getIssuerId(): ParticipantId
    {
        return $this->issuerId;
    }
}
