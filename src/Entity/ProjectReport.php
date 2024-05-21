<?php

namespace App\Entity;

use App\Repository\ProjectReportRepository;
use Doctrine\ORM\Mapping as ORM;

// todo
#[ORM\Entity(repositoryClass: ProjectReportRepository::class)]
class ProjectReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'projectReport', cascade: ['persist', 'remove'])]
    private ?Project $project = null;

    public function getId(): ?int
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
