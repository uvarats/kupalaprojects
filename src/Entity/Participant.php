<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\NameFormatEnum;
use App\Feature\Participant\Repository\ParticipantRepository;
use App\Trait\NameTrait;
use App\ValueObject\PersonName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
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

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'participant')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', nullable: true)]
    private ?User $account = null;

    private function __construct() {}

    public static function make(
        PersonName $name,
        string $educationEstablishment,
        string $email,
    ): Participant {
        $instance = new self();

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

    public function getEducationEstablishment(): ?string
    {
        return $this->educationEstablishment;
    }

    public function getEmail(): ?string
    {
        return $this->email;
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

    public function getLastAndFirstName(): string
    {
        return $this->getName()->format(NameFormatEnum::LAST_FIRST);
    }

    public function getName(): PersonName
    {
        return PersonName::make(
            lastName: $this->lastName,
            firstName: $this->firstName,
            middleName: $this->middleName,
        );
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): static
    {
        $this->account = $account;

        return $this;
    }
}
