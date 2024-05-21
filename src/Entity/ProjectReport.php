<?php

namespace App\Entity;

use App\Repository\ProjectReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

// todo
#[ORM\Entity(repositoryClass: ProjectReportRepository::class)]
class ProjectReport
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\OneToOne(inversedBy: 'projectReport', cascade: ['persist', 'remove'])]
    private ?Project $project = null;

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
}
