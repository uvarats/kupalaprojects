<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Uvarats\Dto\Data;

final class FullNameDto extends Data
{
    public function __construct(
        public readonly string $lastName,
        public readonly string $firstName,
        public readonly ?string $middleName = null,
    ) {
    }

    public static function fromString(string $fullName): FullNameDto
    {
        $data = explode(' ', $fullName);

        return new self(
            lastName: $data[0],
            firstName: $data[1],
            middleName: $data[2] ?? null,
        );
    }

}
