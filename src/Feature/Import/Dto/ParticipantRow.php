<?php

declare(strict_types=1);

namespace App\Feature\Import\Dto;

final readonly class ParticipantRow
{
    public function __construct(
        private int $rowNumber,
        private string $fullName,
        private string $email,
        private string $educationEstablishment,
    ) {}

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }

    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }
}
