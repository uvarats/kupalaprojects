<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AcceptanceEnum;
use App\Enum\NameFormatEnum;
use App\Repository\ParticipantRepository;
use App\Trait\NameTrait;
use App\ValueObject\PersonName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
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
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(length: 255)]
    private string $firstName;

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

    private function __construct() {}

    public static function make(
        Project $project,
        PersonName $name,
        string $educationEstablishment,
        string $email,
    ): Participant {
        $instance = new self();

        $instance->project = $project;
        $instance->lastName = $name->getLastName();
        $instance->firstName = $name->getFirstName();
        $instance->middleName = $name->getMiddleName();
        $instance->educationEstablishment = $educationEstablishment;
        $instance->email = $email;

        return $instance;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): Participant
    {
        $this->team = $team;

        return $this;
    }

    public function getProject(): Project
    {
        if ($this->project === null) {
            throw new \LogicException('Project must not be null');
        }

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

    public function getFirstAndMiddleName(): string
    {
        $personName = $this->getName();

        return $personName->format(NameFormatEnum::FIRST_MIDDLE);
    }

    public function getFullName(): string
    {
        return $this->getName()->format(NameFormatEnum::LAST_FIRST_MIDDLE);
    }

    public function getName(): PersonName
    {
        return PersonName::make(
            lastName: $this->lastName,
            firstName: $this->firstName,
            middleName: $this->middleName,
        );
    }
}
