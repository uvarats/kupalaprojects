<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\ValueObject\Email;
use App\ValueObject\PersonName;

final readonly class TeamOwnerData
{
    public function __construct(
        private PersonName $name,
        private string $educationEstablishment,
        private Email $email,
    ) {}

    public static function make(
        PersonName $name,
        string $educationEstablishment,
        Email $email,
    ): TeamOwnerData {
        if (empty($educationEstablishment)) {
            throw new \DomainException('Team creator must have valid non-empty education establishment');
        }

        return new self(
            name: $name,
            educationEstablishment: $educationEstablishment,
            email: $email,
        );
    }

    public function getName(): PersonName
    {
        return $this->name;
    }

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
