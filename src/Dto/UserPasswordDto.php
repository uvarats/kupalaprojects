<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Uvarats\Dto\Data;

final class UserPasswordDto extends Data
{
    public function __construct(
        private readonly User $user,
        private readonly string $password,
    )
    {
    }
}
