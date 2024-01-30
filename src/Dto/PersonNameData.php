<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class PersonNameData
{
    public function __construct(
        #[Assert\NotBlank]
        public string $lastName,
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $middleName = null,
    ) {
    }

    public static function fromString(string $fullName): PersonNameData
    {
        $data = explode(' ', $fullName);

        return new self(
            lastName: $data[0],
            firstName: $data[1],
            middleName: $data[2] ?? null,
        );
    }

}
