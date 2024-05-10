<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Feature\Account\Service\PasswordGenerator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserService implements UserResolverInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PasswordGenerator $passwordGenerator,
        private Security $security
    ) {}

    public function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();

        if ($user === null) {
            return null;
        }

        if (!$user instanceof User) {
            throw new \LogicException();
        }

        return $user;
    }

    /**
     * @deprecated
     */
    public function generatePassword(User $user): string
    {
        $rawPassword = $this->passwordGenerator->generatePlain();

        $hashedPassword = $this->hasher->hashPassword($user, $rawPassword);
        $user->setPassword($hashedPassword);

        return $rawPassword;
    }

}
