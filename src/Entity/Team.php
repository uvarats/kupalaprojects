<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interface\AcceptableInterface;
use App\Enum\AcceptanceEnum;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team implements AcceptableInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Column(enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION;

    /**
     * @var Collection<int, TeamParticipant>
     */
    #[ORM\OneToMany(targetEntity: TeamParticipant::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $teamParticipants;

    public function __construct()
    {
        $this->teamParticipants = new ArrayCollection();
    }

    public static function create(
        string $name,
        Participant $creator,
        Project $project,
    ): Team {
        $instance = new self();

        $instance->name = $name;
        $instance->project = $project;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function isApproved(): bool
    {
        return $this->acceptance === AcceptanceEnum::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->acceptance === AcceptanceEnum::REJECTED;
    }

    public function isWaitingForDecision(): bool
    {
        return $this->acceptance == AcceptanceEnum::NO_DECISION;
    }

    public function approve(): void
    {
        $this->acceptance = AcceptanceEnum::APPROVED;
    }

    public function reject(): void
    {
        $this->acceptance = AcceptanceEnum::REJECTED;
    }

    public function stage(): void
    {
        $this->acceptance = AcceptanceEnum::NO_DECISION;
    }

    /**
     * @return Collection<int, TeamParticipant>
     */
    public function getTeamParticipants(): Collection
    {
        return $this->teamParticipants;
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
}
