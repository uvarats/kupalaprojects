<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Uvarats\Dto\Data;

final class UserPassword extends Data
{
    public function __construct(
        public readonly User $user,
        public readonly string $password,
    )
    {
    }
}
