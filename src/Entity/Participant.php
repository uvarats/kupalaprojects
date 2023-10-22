<?php

namespace App\Entity;

use App\Enum\AcceptanceEnum;
use App\Repository\ParticipantRepository;
use App\Trait\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[ORM\UniqueConstraint(columns: ['project_id', 'email'])]
#[UniqueEntity(
    fields: ['project', 'email'],
    message: 'Участник с таким e-mail уже зарегистрирован в данном проекте',
)]
class Participant
{
    use NameTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $middleName = null;

    #[ORM\Column(length: 255)]
    private ?string $educationEstablishment = null;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'participants')]
    private ?Team $team = null;

    // not null?
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'participants')]
    private ?Project $project = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getEducationEstablishment(): ?string
    {
        return $this->educationEstablishment;
    }

    public function setEducationEstablishment(string $educationEstablishment): self
    {
        $this->educationEstablishment = $educationEstablishment;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isAccepted(): bool
    {
        return $this->acceptance === AcceptanceEnum::APPROVED;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function setAcceptance(AcceptanceEnum $acceptance): self
    {
        $this->acceptance = $acceptance;

        return $this;
    }
}
