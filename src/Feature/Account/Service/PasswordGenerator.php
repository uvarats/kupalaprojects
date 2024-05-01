<?php

declare(strict_types=1);

namespace App\Feature\Account\Service;

use App\Entity\User;
use App\Feature\Account\ValueObject\Password;
use Nette\Utils\Random;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class PasswordGenerator
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {}

    /**
     * This function does not set password to user automatically.
     * User is needed to determine, which hasher to use according to firewall settings.
     */
    public function generate(User $user): Password
    {
        $plainPassword = $this->generatePlain();

        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);

        return new Password(
            plainPassword: $plainPassword,
            hashedPassword: $hashedPassword,
        );
    }

    public function generatePlain(int $length = 10): string
    {
        return Random::generate($length);
    }
}
