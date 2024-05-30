<?php

declare(strict_types=1);

namespace App\Dto\Form\Project;

use App\Dto\EventDatesData;
use App\Entity\EducationSubGroup;
use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\ProjectAward;
use App\Entity\ProjectSubject;
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

    #[Assert\Valid]
    private ?EventDatesData $dates = null;

    /**
     * @var EducationSubGroup[] $orientedOn
     */
    private array $orientedOn = [];

    /**
     * @var ProjectSubject[] $subjects
     */
    private array $subjects = [];

    // todo: FestivalData (?)
    private ?Festival $festival = null;

    /**
     * @var ProjectAward[] $awards
     */
    private array $awards = [];
    private bool $teamsAllowed = false;

    public static function fromProject(Project $project): ProjectData
    {
        $instance = new self();

        $instance->name = $project->getName();
        $instance->goal = $project->getGoal();
        $instance->siteUrl = $project->getSiteUrl();

        $dates = $project->getDates();
        $instance->dates = EventDatesData::fromDates($dates);
        $instance->orientedOn = $project->getOrientedOn()->toArray();
        $instance->subjects = $project->getSubjects()->toArray();
        $instance->festival = $project->getFestival();
        $instance->awards = $project->getAwards()->toArray();
        $instance->teamsAllowed = $project->isTeamsAllowed();

        return $instance;
    }

    public function __construct() {}

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
     * @return EducationSubGroup[]
     */
    public function getOrientedOn(): array
    {
        return $this->orientedOn;
    }

    public function setOrientedOn(array $orientedOn): void
    {
        $this->orientedOn = $orientedOn;
    }

    /**
     * @return ProjectSubject[]
     */
    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function setSubjects(array $subjects): void
    {
        $this->subjects = $subjects;
    }
}
