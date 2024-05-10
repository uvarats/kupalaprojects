<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use App\Entity\User;

final readonly class CreateAccountParticipantRequest
{
    private function __construct(
        private User $account,
        private string $educationEstablishment,
    ) {}

    public static function forUser(User $user, string $educationEstablishment): self
    {
        if (empty($educationEstablishment)) {
            throw new \DomainException('Education establishment cannot be empty.');
        }

        return new self(
            account: $user,
            educationEstablishment: $educationEstablishment
        );
    }

    public function getAccount(): User
    {
        return $this->account;
    }

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }
}
