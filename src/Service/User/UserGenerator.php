<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Collection\UserCollection;
use App\Entity\User;
use App\Service\Util\PasswordGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserGenerator
{
    private readonly Generator $faker;

    private ?string $defaultPassword = null;

    private bool $isAdmin = false;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly PasswordGeneratorService $passwordGenerator,
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->faker = Factory::create('ru_RU');
    }

    public function generateMass(int $count = 10): UserCollection
    {
        $users = new UserCollection();

        for($i = 0; $i < $count; $i++) {
            $user = $this->generateOne();
            $this->entityManager->persist($user);

            $users->add($user);
        }

        $this->entityManager->flush();

        return $users;
    }

    public function generateOne(bool $save = false): User
    {
        $faker = $this->faker;
        $user = new User();

        $user->setEmail($faker->email())
            ->setLastName($faker->lastName())
            ->setFirstName($faker->firstName());

        if ($this->isAdmin) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        if ($this->defaultPassword !== null) {
            $password = $this->defaultPassword;
        } else {
            $password = $this->passwordGenerator->getRandomPassword();
        }

        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        if ($save) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @param string|null $password null means random password
     */
    public function setDefaultPassword(?string $password): UserGenerator
    {
        $this->defaultPassword = $password;

        return $this;
    }

    public function setIsAdmin(bool $admin): UserGenerator
    {
        $this->isAdmin = $admin;

        return $this;
    }
}
