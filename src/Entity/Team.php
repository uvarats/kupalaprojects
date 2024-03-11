<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Embeddable\Acceptance;
use App\Entity\Interface\AcceptableInterface;
use App\Enum\AcceptanceEnum;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team implements AcceptableInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToOne(targetEntity: Participant::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Participant $teamCreator;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Participant::class)]
    private Collection $participants;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Embedded(class: Acceptance::class, columnPrefix: false)]
    private Acceptance $acceptance;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->acceptance = new Acceptance();
    }

    public static function create(
        string $name,
        Participant $creator,
        Project $project,
    ): Team {
        $instance = new self();

        $instance->name = $name;
        $instance->teamCreator = $creator;
        $instance->project = $project;

        return $instance;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTeamCreator(): Participant
    {
        return $this->teamCreator;
    }


    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setTeam($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getTeam() === $this) {
                $participant->setTeam(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance->getAcceptance();
    }

    public function isApproved(): bool
    {
        return $this->acceptance->isApproved();
    }

    public function isRejected(): bool
    {
        return $this->acceptance->isRejected();
    }

    public function isWaitingForDecision(): bool
    {
        return $this->acceptance->isWaitingForDecision();
    }

    public function approve(): void
    {
        $this->acceptance->approve();
    }

    public function reject(): void
    {
        $this->acceptance->reject();
    }

    public function stage(): void
    {
        $this->acceptance->stage();
    }
}
