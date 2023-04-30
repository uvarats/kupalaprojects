<?php

namespace App\Entity;

use App\Repository\EducationSubGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: EducationSubGroupRepository::class)]
class EducationSubGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EducationGroup $educationGroup = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'orientedOn')]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEducationGroup(): ?EducationGroup
    {
        return $this->educationGroup;
    }

    public function setEducationGroup(?EducationGroup $educationGroup): self
    {
        $this->educationGroup = $educationGroup;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addOrientedOn($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeOrientedOn($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getName();
    }

}
