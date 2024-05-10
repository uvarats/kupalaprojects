<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use App\Entity\User;

final readonly class NewUserMailData
{
    public function __construct(
        public User $user,
        public string $password,
        public string $loginLink,
    ) {}
}
