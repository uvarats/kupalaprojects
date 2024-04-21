<?php

declare(strict_types=1);

namespace App\Dto\Form\Participant;

use App\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;

final class ParticipantData
{
    #[Assert\NotBlank]
    private string $lastName = '';

    #[Assert\NotBlank]
    private string $firstName = '';

    #[Assert\NotBlank(allowNull: true)]
    private ?string $middleName = null;

    #[Assert\NotBlank]
    private string $educationEstablishment = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email = '';

    private ?Project $project = null;

    public function __construct() {}

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }

    public function setEducationEstablishment(string $educationEstablishment): void
    {
        $this->educationEstablishment = $educationEstablishment;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        dd($project, debug_backtrace());
        $this->project = $project;
    }
}
