<?php

declare(strict_types=1);

namespace App\Feature\Core\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserRoleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function grantRole(User $user, string $role): void
    {
        $user->addRole($role);

        $this->entityManager->flush();
    }

    public function revokeRole(User $user, string $role): void
    {
        $user->removeRole($role);

        $this->entityManager->flush();
    }
}
