<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\ValueObject\Email;
use App\ValueObject\PersonName;

final readonly class TeamParticipantData
{
    private function __construct(
        private PersonName $name,
        private string $educationEstablishment,
        private Email $email,
    ) {}

    public static function make(
        PersonName $name,
        string $educationEstablishment,
        Email $email,
    ): TeamParticipantData {
        if (empty($educationEstablishment)) {
            throw new \DomainException('Education establishment can not be empty');
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
