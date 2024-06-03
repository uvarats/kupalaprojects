<?php

declare(strict_types=1);

namespace App\Entity;

use App\Feature\Import\Enum\ImportStatusEnum;
use App\Feature\Import\Enum\ImportTypeEnum;
use App\Feature\Import\Repository\ProjectImportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectImportRepository::class)]
class ProjectImport
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ImportTypeEnum $type;

    #[ORM\Column(length: 255)]
    private ImportStatusEnum $status;

    #[ORM\Column(length: 255)]
    private string $fileName;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $processedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    public function __construct(
        ImportTypeEnum $type,
        string $fileName,
        Project $project,
    ) {
        $this->type = $type;
        $this->status = ImportStatusEnum::NEW;
        $this->fileName = $fileName;
        $this->createdAt = new \DateTimeImmutable();
        $this->project = $project;
    }


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getType(): ImportTypeEnum
    {
        return $this->type;
    }

    public function getStatus(): ImportStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ImportStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFilePath(): string
    {
        return $this->project->filePath($this->fileName);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getProcessedAt(): ?\DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function markAsProcessed(): void
    {
        $this->processedAt = new \DateTimeImmutable();
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
