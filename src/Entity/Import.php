<?php

namespace App\Entity;

use App\Feature\Import\Enum\ImportStatusEnum;
use App\Feature\Import\Enum\ImportTypeEnum;
use App\Feature\Import\Repository\ImportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ImportRepository::class)]
class Import
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ImportTypeEnum $type;

    #[ORM\Column(type: Types::BLOB)]
    private mixed $data;

    #[ORM\Column(length: 255, enumType: ImportStatusEnum::class)]
    private ImportStatusEnum $status = ImportStatusEnum::NEW;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    public function __construct(ImportTypeEnum $type, mixed $data)
    {
        $this->type = $type;
        $this->data = $data;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getType(): ImportTypeEnum
    {
        return $this->type;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
