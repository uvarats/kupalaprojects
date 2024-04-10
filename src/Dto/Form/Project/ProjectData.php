<?php

declare(strict_types=1);

namespace App\Dto\Form\Project;

use App\Dto\EventDatesData;
use App\Entity\EducationSubGroup;
use App\Entity\Festival;
use App\Entity\ProjectAward;
use App\Entity\ProjectSubject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectData
{
    #[Assert\NotBlank]
    private string $name = '';
    #[Assert\NotBlank]
    private string $goal = '';
    #[Assert\NotBlank]
    #[Assert\Url]
    private ?string $siteUrl = null;

    private ?EventDatesData $dates = null;

    #[Assert\NotBlank]
    private ?int $creationYear = null;

    /**
     * @var Collection<array-key, EducationSubGroup> $orientedOn
     */
    private Collection $orientedOn;

    /**
     * @var Collection<array-key, ProjectSubject> $subjects
     */
    private Collection $subjects;

    // todo: FestivalData (?)
    private ?Festival $festival = null;

    /**
     * @var ProjectAward[] $awards
     */
    private array $awards = [];
    private bool $teamsAllowed = false;

    public function __construct()
    {
        $this->orientedOn = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGoal(): string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): void
    {
        $this->goal = $goal;
    }

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(?string $siteUrl): void
    {
        $this->siteUrl = $siteUrl;
    }

    public function getCreationYear(): ?int
    {
        return $this->creationYear;
    }

    public function setCreationYear(?int $creationYear): void
    {
        $this->creationYear = $creationYear;
    }


    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): void
    {
        $this->festival = $festival;
    }

    /**
     * @return ProjectAward[]
     */
    public function getAwards(): array
    {
        return $this->awards;
    }

    public function setAwards(array $awards): void
    {
        $this->awards = $awards;
    }

    public function isTeamsAllowed(): bool
    {
        return $this->teamsAllowed;
    }

    public function setTeamsAllowed(bool $teamsAllowed): void
    {
        $this->teamsAllowed = $teamsAllowed;
    }

    public function getDates(): ?EventDatesData
    {
        return $this->dates;
    }

    public function setDates(?EventDatesData $dates): void
    {
        $this->dates = $dates;
    }

    /**
     * @return Collection<array-key, EducationSubGroup>
     */
    public function getOrientedOn(): Collection
    {
        return $this->orientedOn;
    }

    public function setOrientedOn(Collection $orientedOn): void
    {
        $this->orientedOn = $orientedOn;
    }

    /**
     * @return Collection<array-key, ProjectSubject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function setSubjects(Collection $subjects): void
    {
        $this->subjects = $subjects;
    }
}
