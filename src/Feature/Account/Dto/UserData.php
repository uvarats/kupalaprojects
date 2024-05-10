<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserData
{
    #[Assert\NotBlank]
    private string $lastName = '';
    #[Assert\NotBlank]
    private string $firstName = '';

    #[Assert\NotBlank(allowNull: true)]
    private ?string $middleName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email = '';

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
