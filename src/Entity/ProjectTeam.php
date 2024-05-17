<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interface\AcceptableInterface;
use App\Entity\Trait\AcceptableTrait;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Repository\ProjectTeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectTeamRepository::class)]
#[ORM\UniqueConstraint(fields: ['team', 'project'])]
class ProjectTeam implements AcceptableInterface
{
    use AcceptableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

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

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }
}
