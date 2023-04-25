<?php

declare(strict_types=1);

namespace App\Entity\NullEntity;

use App\Entity\Interface\ProjectAuthorInterface;
use App\Entity\User;

final class NullProjectAuthor implements ProjectAuthorInterface
{

    public function getUserEntity(): ?User
    {
        return null;
    }
}
