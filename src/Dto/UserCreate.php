<?php

declare(strict_types=1);

namespace App\Dto;

use Uvarats\Dto\Data;

final class UserCreate extends Data
{
    public function __construct(
        public string $fullName,
        public string $email,
        public string $password,
        public bool $isAdmin = false,
    ) {}
}
