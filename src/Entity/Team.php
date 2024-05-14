<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AcceptanceEnum;
use App\Enum\TeamParticipantRoleEnum;
use App\Feature\Team\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    /**
     * @var Collection<int, TeamParticipant>
     */
    #[ORM\OneToMany(targetEntity: TeamParticipant::class, mappedBy: 'team', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $teamParticipants;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(options: ['default' => false])]
    private bool $archived = false;

    public function __construct()
    {
        $this->teamParticipants = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public static function create(
        string $name,
        Participant $creator,
    ): Team {
        $instance = new self();

        $instance->name = $name;
        $creator = new TeamParticipant(
            $creator,
            $instance,
            TeamParticipantRoleEnum::CREATOR,
            new \DateTimeImmutable(),
        );

        $instance->addTeamParticipant($creator);

        return $instance;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TeamParticipant>
     */
    public function getTeamParticipants(): Collection
    {
        return $this->teamParticipants;
    }

    public function addGeneralParticipant(Participant $participant): void
    {
        $teamParticipant = TeamParticipant::makeGeneralParticipant($participant, $this);

        $this->addTeamParticipant($teamParticipant);
    }

    public function hasParticipant(Participant $participant): bool
    {
        foreach ($this->teamParticipants as $teamParticipant) {
            if ($teamParticipant->getParticipant() === $participant) {
                return true;
            }
        }

        return false;
    }

    public function addTeamParticipant(TeamParticipant $teamParticipant): static
    {
        if (!$this->teamParticipants->contains($teamParticipant)) {
            $this->teamParticipants->add($teamParticipant);
        }

        return $this;
    }

    public function removeTeamParticipant(TeamParticipant $teamParticipant): static
    {
        $this->teamParticipants->removeElement($teamParticipant);

        return $this;
    }

    public function isCreator(Participant $participant): bool{
        $creator = $this->getCreator();

        return $creator->getParticipant() === $participant;
    }

    private function getCreator(): TeamParticipant
    {
        foreach ($this->teamParticipants as $teamParticipant) {
            if ($teamParticipant->getRole() === TeamParticipantRoleEnum::CREATOR) {
                return $teamParticipant;
            }
        }

        throw new \LogicException('Team creator not found. What? This is impossible case. What did you do?');
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function archive(): void
    {
        $this->archived = true;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
