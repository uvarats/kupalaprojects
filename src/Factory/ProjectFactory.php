<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;

final class ProjectFactory
{
    public function create(?User $user) {
        if ($user === null) {
            $user = new User();
        }
    }
}
