<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interface\AcceptableInterface;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectParticipantRepository::class)]
#[ORM\UniqueConstraint(fields: ['project', 'participant'])]
class ProjectParticipant implements AcceptableInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION;

    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'participants')]
        #[ORM\JoinColumn(nullable: false)]
        private Project $project,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private Participant $participant,
    ) {}

    public static function make(Project $project, Participant $participant): self
    {
        return new self($project, $participant);
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function approve(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $this->acceptance = AcceptanceEnum::APPROVED;
    }

    public function reject(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $this->acceptance = AcceptanceEnum::REJECTED;
    }

    public function retractDecision(): void
    {
        $this->acceptance = AcceptanceEnum::NO_DECISION;
    }

    public function isApproved(): bool
    {
        return $this->acceptance === AcceptanceEnum::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->acceptance === AcceptanceEnum::REJECTED;
    }

    public function isPending(): bool
    {
        return $this->acceptance === AcceptanceEnum::NO_DECISION;
    }
}
