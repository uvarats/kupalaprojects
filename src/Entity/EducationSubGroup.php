<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EducationSubGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EducationSubGroupRepository::class)]
class EducationSubGroup
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // todo: make unidirectional relation. Sub Group must not know about projects :)
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'orientedOn')]
    private Collection $projects;

    #[ORM\Column(options: ['default' => true])]
    private bool $allowsProjects = true;

    #[ORM\OneToMany(targetEntity: EducationSubGroup::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: EducationSubGroup::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private ?EducationSubGroup $parent = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?Uuid
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

    public function isAllowsProjects(): ?bool
    {
        return $this->allowsProjects;
    }

    public function setAllowsProjects(bool $allowsProjects): static
    {
        $this->allowsProjects = $allowsProjects;

        return $this;
    }

    public function getParent(): ?EducationSubGroup
    {
        return $this->parent;
    }

    public function setParent(?EducationSubGroup $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

}
