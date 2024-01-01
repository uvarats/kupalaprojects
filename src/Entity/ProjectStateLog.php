<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ProjectStateEnum;
use App\Repository\ProjectStateLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectStateLogRepository::class)]
class ProjectStateLog
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Column(length: 255, enumType: ProjectStateEnum::class)]
    private ProjectStateEnum $fromState;

    #[ORM\Column(length: 255, enumType: ProjectStateEnum::class)]
    private ProjectStateEnum $toState;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne]
    private ?User $performedBy = null;

    private function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function make(
        Project $project,
        ProjectStateEnum $fromState,
        ProjectStateEnum $toState,
        ?User $performedBy,
    ): ProjectStateLog {
        $instance = new self();

        $instance->project = $project;
        $instance->fromState = $fromState;
        $instance->toState = $toState;
        $instance->performedBy = $performedBy;

        return $instance;
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

    public function getFromState(): ProjectStateEnum
    {
        return $this->fromState;
    }

    public function setFromState(ProjectStateEnum $fromState): static
    {
        $this->fromState = $fromState;

        return $this;
    }

    public function getToState(): ProjectStateEnum
    {
        return $this->toState;
    }

    public function setToState(ProjectStateEnum $toState): static
    {
        $this->toState = $toState;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function performedBy(): ?User
    {
        return $this->performedBy;
    }
}
