<?php

declare(strict_types=1);

namespace App\Entity;

use App\Feature\Project\Repository\ProjectReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

// todo
#[ORM\Entity(repositoryClass: ProjectReportRepository::class)]
class ProjectReport
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, ProjectParticipant>
     */
    #[ORM\JoinTable(name: 'project_report_finalists')]
    #[ORM\JoinColumn(name: 'project_report_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'project_participant_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: ProjectParticipant::class)]
    private Collection $finalists;

    public function __construct(
        #[ORM\OneToOne(inversedBy: 'projectReport', cascade: ['persist', 'remove'])]
        private Project $project,
        #[ORM\Column(length: 255)]
        private string $reportUrl,
        #[ORM\Column(length: 255)]
        private string $protocolUrl,
        #[ORM\Column(length: 255)]
        private string $newsUrl,
    ) {
        $this->finalists = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getReportUrl(): string
    {
        return $this->reportUrl;
    }

    public function setReportUrl(string $reportUrl): static
    {
        $this->reportUrl = $reportUrl;

        return $this;
    }

    public function getProtocolUrl(): string
    {
        return $this->protocolUrl;
    }

    public function setProtocolUrl(string $protocolUrl): static
    {
        $this->protocolUrl = $protocolUrl;

        return $this;
    }

    public function getNewsUrl(): string
    {
        return $this->newsUrl;
    }

    public function setNewsUrl(string $newsUrl): static
    {
        $this->newsUrl = $newsUrl;

        return $this;
    }

    /**
     * @return Collection<int, ProjectParticipant>
     */
    public function getFinalists(): Collection
    {
        return $this->finalists;
    }

    public function addFinalist(ProjectParticipant $finalist): static
    {
        if ($finalist->getProject() !== $this->project) {
            throw new \LogicException('Cannot add participant, that participates another project');
        }

        if (!$this->finalists->contains($finalist)) {
            $this->finalists->add($finalist);
        }

        return $this;
    }

    public function removeFinalist(ProjectParticipant $finalist): static
    {
        $this->finalists->removeElement($finalist);

        return $this;
    }

    public function syncFinalists(array $finalists): void
    {
        $this->finalists = new ArrayCollection();

        foreach ($finalists as $finalist) {
            $this->addFinalist($finalist);
        }
    }
}
