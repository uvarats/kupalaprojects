<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TeamParticipantRoleEnum;
use App\Feature\Team\Repository\TeamParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamParticipantRepository::class)]
#[ORM\UniqueConstraint(fields: ['participant', 'team'])]
class TeamParticipant
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $leftAt = null;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Participant $participant,
        #[ORM\ManyToOne(inversedBy: 'teamParticipants')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private readonly Team $team,
        #[ORM\Column(length: 255, enumType: TeamParticipantRoleEnum::class)]
        private TeamParticipantRoleEnum $role,
        #[ORM\Column]
        private readonly \DateTimeImmutable $joinedAt,
    ) {}

    public static function makeGeneralParticipant(Participant $participant, Team $team): self
    {
        return new self(
            participant: $participant,
            team: $team,
            role: TeamParticipantRoleEnum::GENERAL_PARTICIPANT,
            joinedAt: new \DateTimeImmutable(),
        );
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getRole(): TeamParticipantRoleEnum
    {
        return $this->role;
    }

    public function setRole(TeamParticipantRoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isCreator(): bool
    {
        return $this->role === TeamParticipantRoleEnum::CREATOR;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getLeftAt(): ?\DateTimeImmutable
    {
        return $this->leftAt;
    }

    public function setLeftAt(?\DateTimeImmutable $leftAt): static
    {
        $this->leftAt = $leftAt;

        return $this;
    }
}
