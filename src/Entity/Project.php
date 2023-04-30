<?php

namespace App\Entity;

use App\Interface\DateRangeInterface;
use App\Repository\ProjectRepository;
use App\Validator\DateRangeValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Assert\Callback([DateRangeValidator::class, 'validate'])]
class Project implements DateRangeInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $siteUrl = null;

    #[ORM\Column]
    private ?int $creationYear = null;

    #[ORM\ManyToMany(targetEntity: ProjectSubject::class, inversedBy: 'projects')]
    private Collection $subjects;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectAuthor $author = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectAward::class, orphanRemoval: true)]
    private Collection $awards;

    #[ORM\Column(length: 50)]
    private ?string $state = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startsAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endsAt = null;

    #[ORM\ManyToMany(targetEntity: EducationSubGroup::class, inversedBy: 'projects')]
    private Collection $orientedOn;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->orientedOn = new ArrayCollection();
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

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(string $siteUrl): self
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    public function getCreationYear(): ?int
    {
        return $this->creationYear;
    }

    public function setCreationYear(int $creationYear): self
    {
        $this->creationYear = $creationYear;

        return $this;
    }

    /**
     * @return Collection<int, ProjectSubject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(ProjectSubject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
        }

        return $this;
    }

    public function removeSubject(ProjectSubject $subject): self
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): self
    {
        $this->festival = $festival;

        return $this;
    }

    public function getAuthor(): ?ProjectAuthor
    {
        return $this->author;
    }

    public function setAuthor(?ProjectAuthor $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, ProjectAward>
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(ProjectAward $award): self
    {
        if (!$this->awards->contains($award)) {
            $this->awards->add($award);
            $award->setProject($this);
        }

        return $this;
    }

    public function removeAward(ProjectAward $award): self
    {
        if ($this->awards->removeElement($award)) {
            // set the owning side to null (unless already changed)
            if ($award->getProject() === $this) {
                $award->setProject(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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
     * @return Collection<int, EducationSubGroup>
     */
    public function getOrientedOn(): Collection
    {
        return $this->orientedOn;
    }

    public function addOrientedOn(EducationSubGroup $orientedOn): self
    {
        if (!$this->orientedOn->contains($orientedOn)) {
            $this->orientedOn->add($orientedOn);
        }

        return $this;
    }

    public function removeOrientedOn(EducationSubGroup $orientedOn): self
    {
        $this->orientedOn->removeElement($orientedOn);

        return $this;
    }
}
