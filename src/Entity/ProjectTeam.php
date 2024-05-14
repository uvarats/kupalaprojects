<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AcceptanceEnum;
use App\Feature\Project\Repository\ProjectTeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectTeamRepository::class)]
#[ORM\UniqueConstraint(fields: ['team', 'project'])]
class ProjectTeam
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Team $team,
        #[ORM\ManyToOne(inversedBy: 'teams')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Project $project,
    ) {}

    public static function create(Team $team, Project $project): self
    {
        return new self($team, $project);
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function approve(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            throw new \LogicException('Cannot approve team whose decision has already been made');
        }

        $this->acceptance = AcceptanceEnum::APPROVED;
    }

    public function reject(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            throw new \LogicException('Cannot reject team whose decision has already been made');
        }

        $this->acceptance = AcceptanceEnum::REJECTED;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function isRejected(): bool
    {
        return $this->acceptance === AcceptanceEnum::REJECTED;
    }
}
