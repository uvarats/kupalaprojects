<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Embeddable\PersonName;
use App\Enum\NameFormatEnum;
use App\Repository\UserRepository;
use App\Trait\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use NameTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];
    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $middleName = null;

    #[ORM\OneToOne(mappedBy: 'userEntity', targetEntity: ProjectAuthor::class, cascade: ['persist', 'remove'])]
    private ?ProjectAuthor $projectAuthor = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getProjectAuthor(): ?ProjectAuthor
    {
        return $this->projectAuthor;
    }

    public function setProjectAuthor(ProjectAuthor $projectAuthor): self
    {
        // set the owning side of the relation if necessary
        if ($projectAuthor->getUserEntity() !== $this) {
            $projectAuthor->setUserEntity($this);
        }

        $this->projectAuthor = $projectAuthor;

        return $this;
    }

    public function getPersonName(): PersonName
    {
        return PersonName::make(
            lastName: $this->lastName,
            firstName: $this->firstName,
            middleName: $this->middleName,
        );
    }

    public function getFullName(): string
    {
        return $this->getPersonName()->format(NameFormatEnum::LAST_FIRST_MIDDLE);
    }

    public function getDisplayString(): string
    {
        $email = $this->getEmail();
        $fullName = $this->getPersonName()->format(NameFormatEnum::LAST_FIRST_MIDDLE);

        return $fullName . " ({$email})";
    }

    public function __toString(): string
    {
        return $this->getDisplayString();
    }

}
