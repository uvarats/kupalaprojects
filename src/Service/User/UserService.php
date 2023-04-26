<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Dto\UserPasswordDto;
use App\Entity\User;
use App\Service\Util\PasswordGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PasswordGeneratorService $passwordGenerator,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function processUser(User $user): UserPasswordDto
    {
        $rawPassword = $this->generatePassword($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new UserPasswordDto(
            user: $user,
            password: $rawPassword
        );
    }

    public function generatePassword(User $user): string
    {
        $rawPassword = $this->passwordGenerator->getRandomPassword();

        $hashedPassword = $this->hasher->hashPassword($user, $rawPassword);
        $user->setPassword($hashedPassword);

        return $rawPassword;

    }
}
