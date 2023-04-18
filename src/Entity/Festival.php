<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
class Festival
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startsAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endsAt = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'festival_committee_member')]
    private Collection $organizationCommittee;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'festival_jury_member')]
    private Collection $jury;

    #[ORM\OneToMany(mappedBy: 'festival', targetEntity: Project::class)]
    private Collection $projects;

    public function __construct()
    {
        $this->organizationCommittee = new ArrayCollection();
        $this->jury = new ArrayCollection();
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

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeImmutable $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeImmutable $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getOrganizationCommittee(): Collection
    {
        return $this->organizationCommittee;
    }

    public function addOrganizationCommittee(User $organizationCommittee): self
    {
        if (!$this->organizationCommittee->contains($organizationCommittee)) {
            $this->organizationCommittee->add($organizationCommittee);
        }

        return $this;
    }

    public function removeOrganizationCommittee(User $organizationCommittee): self
    {
        $this->organizationCommittee->removeElement($organizationCommittee);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getJury(): Collection
    {
        return $this->jury;
    }

    public function addJury(User $jury): self
    {
        if (!$this->jury->contains($jury)) {
            $this->jury->add($jury);
        }

        return $this;
    }

    public function removeJury(User $jury): self
    {
        $this->jury->removeElement($jury);

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
            $project->setFestival($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getFestival() === $this) {
                $project->setFestival(null);
            }
        }

        return $this;
    }
}
