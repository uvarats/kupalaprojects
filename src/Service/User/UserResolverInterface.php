<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;

interface UserResolverInterface
{
    public function getCurrentUser(): ?User;
}
