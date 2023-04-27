<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Dto\UserPassword;
use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\User;
use App\Form\ProjectAuthorType;
use App\Service\Util\PasswordGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Twig\Environment;

final readonly class UserService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PasswordGeneratorService $passwordGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handleProjectAuthor(ProjectAuthor $author): void
    {
        $user = $author->getUserEntity();

    }

    public function processUser(User $user): UserPassword
    {
        $rawPassword = $this->generatePassword($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new UserPassword(
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
