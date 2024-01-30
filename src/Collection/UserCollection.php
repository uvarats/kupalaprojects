<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\User;

/**
 * @extends Collection<User>
 */
final class UserCollection extends Collection
{
    public function getType(): string
    {
        return User::class;
    }
}
