<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ProjectStateEnum;
use App\Feature\Project\Collection\ProjectParticipantCollection;
use App\Feature\Project\Collection\ProjectTeamCollection;
use App\Feature\Project\Repository\ProjectRepository;
use App\Interface\HasDateRangeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project implements HasDateRangeInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $siteUrl;

    #[ORM\ManyToMany(targetEntity: ProjectSubject::class, inversedBy: 'projects')]
    private Collection $subjects;

    #[ORM\ManyToOne(targetEntity: Festival::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private Festival $festival;

    #[ORM\ManyToOne(targetEntity: ProjectAuthor::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ProjectAuthor $author;

    /**
     * @var Collection<ProjectAward>
     */
    #[ORM\OneToMany(targetEntity: ProjectAward::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $awards;

    #[ORM\Column(length: 50)]
    private string $state;

    #[ORM\Embedded(columnPrefix: false)]
    #[Assert\Valid]
    private EventDates $dates;

    #[ORM\ManyToMany(targetEntity: EducationSubGroup::class, inversedBy: 'projects')]
    private Collection $orientedOn;

    #[ORM\Column(type: Types::TEXT)]
    private string $goal;

    #[ORM\Column(options: ['default' => false])]
    private bool $teamsAllowed = false;

    /**
     * @var Collection<int, ProjectParticipant>
     */
    #[ORM\OneToMany(targetEntity: ProjectParticipant::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $participants;

    /**
     * @var Collection<int, ProjectTeam>
     */
    #[ORM\OneToMany(targetEntity: ProjectTeam::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $teams;

    #[ORM\OneToOne(mappedBy: 'project', cascade: ['persist', 'remove'])]
    private ?ProjectReport $projectReport = null;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->orientedOn = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public static function create(
        string $name,
        string $siteUrl,
        EventDates $dates,
        Festival $festival,
        ProjectAuthor $author,
        ProjectStateEnum $state,
        string $goal,
        bool $teamsAllowed = false,
    ): Project {
        $project = new Project();

        $project->name = $name;
        $project->siteUrl = $siteUrl;
        $project->dates = $dates;
        $project->festival = $festival;
        $project->author = $author;
        $project->state = $state->value;
        $project->goal = $goal;
        $project->teamsAllowed = $teamsAllowed;

        return $project;
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

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(string $siteUrl): self
    {
        $this->siteUrl = $siteUrl;

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

    public function clearSubjects(): void
    {
        $this->subjects = new ArrayCollection();
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

    public function clearAwards(): void
    {
        $this->awards = new ArrayCollection();
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

    /**
     * Do not remove this method. It is used for elastic index
     */
    public function isActive(): bool
    {
        $stateString = $this->state;
        $state = ProjectStateEnum::from($stateString);

        return $this->festival->isActive()
            && $state !== ProjectStateEnum::UNDER_MODERATION
            && $state !== ProjectStateEnum::REJECTED;
    }

    public function getStartsAt(): \DateTimeImmutable
    {
        return $this->dates->getStartsAt();
    }

    public function getEndsAt(): \DateTimeImmutable
    {
        return $this->dates->getEndsAt();
    }

    public function getDates(): EventDates
    {
        return $this->dates;
    }

    public function setDates(EventDates $dates): Project
    {
        $this->dates = $dates;

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

    public function clearOrientedOn(): void
    {
        $this->orientedOn = new ArrayCollection();
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function isTeamsAllowed(): bool
    {
        return $this->teamsAllowed;
    }

    public function allowTeams(): Project
    {
        $this->teamsAllowed = true;

        return $this;
    }

    public function disallowTeams(): Project
    {
        $this->teamsAllowed = false;

        return $this;
    }

    /**
     * @return Collection<int, ProjectParticipant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * Used in project details template (details.html.twig)
     */
    public function getApprovedParticipants(): ProjectParticipantCollection
    {
        $approvedParticipants = $this->participants->filter(function (ProjectParticipant $participant): bool {
            return $participant->isApproved();
        })->toArray();

        return new ProjectParticipantCollection($approvedParticipants);
    }

    /**
     * Used in project details template (details.html.twig)
     */
    public function getApprovedTeams(): ProjectTeamCollection
    {
        $approvedTeams = $this->teams->filter(function (ProjectTeam $team): bool {
            return $team->isApproved();
        })->toArray();

        return new ProjectTeamCollection($approvedTeams);
    }

    public function submitParticipant(Participant $participant): ProjectParticipant
    {
        $projectParticipant = ProjectParticipant::make($this, $participant);

        $this->participants->add($projectParticipant);

        return $projectParticipant;
    }

    public function hasApprovedParticipant(Participant $participant): bool
    {
        $projectParticipant = $this->findIndividualParticipant($participant);

        return $projectParticipant !== null && $projectParticipant->isApproved();
    }

    public function hasRejectedParticipant(Participant $participant): bool
    {
        $projectParticipant = $this->findIndividualParticipant($participant);

        return $projectParticipant !== null && $projectParticipant->isRejected();
    }

    public function hasPendingParticipant(Participant $participant): bool
    {
        $projectParticipant = $this->findIndividualParticipant($participant);

        return $projectParticipant !== null && $projectParticipant->isPending();
    }

    private function findIndividualParticipant(Participant $participant): ?ProjectParticipant
    {
        foreach ($this->participants as $projectParticipant) {
            $comparableParticipant = $projectParticipant->getParticipant();

            if ($comparableParticipant === $participant) {
                return $projectParticipant;
            }
        }

        return null;
    }

    public function canAcceptParticipant(Participant $participant): bool
    {
        return !$this->hasIndividualParticipant($participant) && !$this->hasTeamParticipant($participant);
    }

    public function hasParticipant(Participant $participant): bool
    {
        return $this->hasIndividualParticipant($participant) || $this->hasTeamParticipant($participant);
    }

    public function hasIndividualParticipant(Participant $participant): bool
    {
        return $this->findIndividualParticipant($participant) !== null;
    }

    public function hasTeamParticipant(Participant $participant): bool
    {
        $projectTeam = $this->findTeamByParticipant($participant);

        return $projectTeam !== null && !$projectTeam->isRejected();
    }

    private function findTeamByParticipant(Participant $participant): ?ProjectTeam
    {
        foreach ($this->teams as $team) {
            // if team rejected, then it is possible to apply through another team (?)
            if ($team->getTeam()->hasParticipant($participant)) {
                return $team;
            }
        }

        return null;
    }

    public function retractParticipant(Participant $participant): void
    {
        $projectParticipant = $this->findIndividualParticipant($participant);

        if ($projectParticipant === null) {
            return;
        }

        if (!$projectParticipant->isPending()) {
            return;
        }

        $this->participants->removeElement($projectParticipant);
    }

    /**
     * @return Collection<int, ProjectTeam>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function submitTeam(Team $team): void
    {
        if ($this->hasTeam($team)) {
            return;
        }

        $projectTeam = ProjectTeam::create($team, $this);
        $this->teams->add($projectTeam);
    }

    public function hasTeam(Team $team): bool
    {
        return $this->findTeam($team) !== null;
    }

    private function findTeam(Team $team): ?ProjectTeam
    {
        foreach ($this->teams as $projectTeam) {
            if ($projectTeam->getTeam() === $team) {
                return $projectTeam;
            }
        }

        return null;
    }

    public function hasRejectedTeam(Team $team): bool
    {
        $projectTeam = $this->findTeam($team);

        return $projectTeam?->isRejected() ?? false;
    }

    public function getProjectReport(): ?ProjectReport
    {
        return $this->projectReport;
    }

    public function setProjectReport(?ProjectReport $projectReport): static
    {
        // unset the owning side of the relation if necessary
        if ($projectReport === null && $this->projectReport !== null) {
            $this->projectReport->setProject(null);
        }

        // set the owning side of the relation if necessary
        if ($projectReport !== null && $projectReport->getProject() !== $this) {
            $projectReport->setProject($this);
        }

        $this->projectReport = $projectReport;

        return $this;
    }
}
