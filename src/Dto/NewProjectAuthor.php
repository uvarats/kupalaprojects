<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;

final readonly class NewProjectAuthor
{
    public function __construct(
        public User $user,
        public string $password,
        public string $loginLink,
    )
    {
    }
}
