<?php

declare(strict_types=1);

namespace App\Dto\Form\Participant;

use App\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @deprecated
 */
final class TeamData
{
    #[Assert\NotBlank]
    private string $name = '';

    #[Assert\NotNull]
    #[Assert\Valid]
    private ?ParticipantData $teamCreator = null;

    /**
     * @var ParticipantData[] $participants
     */
    #[Assert\Valid]
    private array $participants = [];
    private ?Project $project = null;

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

    public function removeParticipant(ParticipantData $data): void
    {
        foreach ($this->participants as $index => $participant) {
            if ($data === $participant) {
                unset($this->participants[$index]);

                break;
            }
        }
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }


}
