<?php

namespace App\Entity;

use App\Entity\Interface\ProjectAuthorInterface;
use App\Repository\ProjectAuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectAuthorRepository::class)]
class ProjectAuthor implements ProjectAuthorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'projectAuthor', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userEntity = null;

    #[ORM\Column(length: 255)]
    private ?string $placeOfWork = null;

    #[ORM\Column(length: 255)]
    private ?string $occupation = null;

    #[ORM\Column(length: 75, nullable: true)]
    private ?string $reserveEmail = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Project::class)]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEntity(): ?User
    {
        return $this->userEntity;
    }

    public function setUserEntity(User $userEntity): self
    {
        $this->userEntity = $userEntity;

        return $this;
    }

    public function getPlaceOfWork(): ?string
    {
        return $this->placeOfWork;
    }

    public function setPlaceOfWork(string $placeOfWork): self
    {
        $this->placeOfWork = $placeOfWork;

        return $this;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): self
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getReserveEmail(): ?string
    {
        return $this->reserveEmail;
    }

    public function setReserveEmail(?string $reserveEmail): self
    {
        $this->reserveEmail = $reserveEmail;

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
            $project->setAuthor($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getAuthor() === $this) {
                $project->setAuthor(null);
            }
        }

        return $this;
    }
}
