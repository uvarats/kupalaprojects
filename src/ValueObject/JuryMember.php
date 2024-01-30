<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Entity\Festival;
use App\Entity\User;

final readonly class JuryMember
{
    private function __construct(
        private User $user,
        private Festival $festival,
    ) {}

    public static function make(User $user, Festival $festival): JuryMember
    {
        if ($festival->isJuryMember($user)) {
            throw new \DomainException('User is not an jury member of festival ' . $festival->getName());
        }

        return new self(user: $user, festival: $festival);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFestival(): Festival
    {
        return $this->festival;
    }
}
