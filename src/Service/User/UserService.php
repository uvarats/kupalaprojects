<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Service\Util\PasswordGeneratorService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PasswordGeneratorService $passwordGenerator,
        private Security $security
    ) {}

    public function getCurrentUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException();
        }

        return $user;
    }

    public function generatePassword(User $user): string
    {
        $rawPassword = $this->passwordGenerator->getRandomPassword();

        $hashedPassword = $this->hasher->hashPassword($user, $rawPassword);
        $user->setPassword($hashedPassword);

        return $rawPassword;
    }

}
