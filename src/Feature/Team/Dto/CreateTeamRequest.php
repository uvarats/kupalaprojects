<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\Feature\Team\Collection\TeamParticipantCollection;
use App\ValueObject\Entity\ProjectId;

final readonly class CreateTeamRequest
{
    private function __construct(
        private string $teamName,
        private ProjectId $projectId,
        private TeamOwnerData $owner,
        private TeamParticipantCollection $participants,
    ) {}

    public static function make(
        string $teamName,
        ProjectId $projectId,
        TeamOwnerData $owner,
    ): CreateTeamRequest {
        if (empty($teamName)) {
            throw new \DomainException('Team name can not be empty');
        }

        return new self(
            teamName: $teamName,
            projectId: $projectId,
            owner: $owner,
            participants: new TeamParticipantCollection(),
        );
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getOwner(): TeamOwnerData
    {
        return $this->owner;
    }

    public function getParticipants(): TeamParticipantCollection
    {
        return $this->participants;
    }

    public function addParticipant(TeamParticipantData $teamParticipantData): void
    {
        $email = $teamParticipantData->getEmail()->toString();

        $this->participants->offsetSet($email, $teamParticipantData);
    }
}
