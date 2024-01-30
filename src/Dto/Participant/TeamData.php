<?php

declare(strict_types=1);

namespace App\Dto\Participant;

use Symfony\Component\Validator\Constraints as Assert;

final class TeamData
{
    #[Assert\NotBlank]
    private string $name = '';

    #[Assert\NotNull]
    private ?ParticipantData $teamCreator = null;

    /**
     * @var ParticipantData[] $participants
     */
    private array $participants = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTeamCreator(): ?ParticipantData
    {
        return $this->teamCreator;
    }

    public function setTeamCreator(?ParticipantData $teamCreator): void
    {
        $this->teamCreator = $teamCreator;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function addParticipant(ParticipantData $data): void
    {
        $this->participants[] = $data;
    }


}
