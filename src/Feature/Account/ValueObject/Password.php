<?php

declare(strict_types=1);

namespace App\Feature\Account\ValueObject;

final readonly class Password
{
    public function __construct(
        private string $plainPassword,
        private string $hashedPassword,
    ) {}

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

}
