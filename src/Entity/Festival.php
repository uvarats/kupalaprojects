<?php

namespace App\Entity;

use App\Interface\DateRangeInterface;
use App\Repository\FestivalRepository;
use App\Validator\DateRangeValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
#[Assert\Callback([DateRangeValidator::class, 'validate'])]
class Festival implements DateRangeInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
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

    #[ORM\Column]
    private bool $isActive = true;

    public function __construct()
    {
        $this->organizationCommittee = new ArrayCollection();
        $this->jury = new ArrayCollection();
        $this->projects = new ArrayCollection();
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

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLabel(): string
    {
        $name = $this->getName();
        $startsAt = $this->getStartsAt();
        $endsAt = $this->getEndsAt();

        $startsAtFormat = $startsAt->format('d.m.Y');
        $endsAtFormat = $endsAt->format('d.m.Y');

        return "{$name} ({$startsAtFormat} - {$endsAtFormat})";
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }
}
