<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class UserCreate
{
    public function __construct(
        public string $fullName,
        public string $email,
        public string $password,
        public bool $isAdmin = false,
    ) {
    }
}
