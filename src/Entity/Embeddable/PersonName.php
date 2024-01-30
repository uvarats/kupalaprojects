<?php

declare(strict_types=1);

namespace App\Entity\Embeddable;

use App\Enum\NameFormatEnum;
use App\Exception\Name\EmptyNameException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated Do not use embeddables, use raw types in entites, but getters should return value objects
 * @see \App\ValueObject\PersonName
 */
#[ORM\Embeddable]
final class PersonName
{
    private function __construct(
        #[ORM\Column(length: 255)]
        private string $lastName,
        #[ORM\Column(length: 255)]
        private string $firstName,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $middleName = null,
    ) {}

    public static function make(
        string $lastName,
        string $firstName,
        ?string $middleName = null,
    ): PersonName {
        if (empty($lastName)) {
            throw EmptyNameException::lastName();
        }

        if (empty($firstName)) {
            throw EmptyNameException::firstName();
        }

        if ($middleName !== null && empty($middleName)) {
            throw EmptyNameException::middleName();
        }

        return new self(
            lastName: $lastName,
            firstName: $firstName,
            middleName: $middleName,
        );
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function changeLastName(string $lastName): void
    {
        if (empty($lastName)) {
            throw EmptyNameException::lastName();
        }

        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function changeFirstName(string $firstName): void
    {
        if (empty($firstName)) {
            throw EmptyNameException::firstName();
        }
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function __toString(): string
    {
        return $this->format(NameFormatEnum::LAST_FIRST_MIDDLE);
    }

    /**
     * If the middle name is null, then formatted string will not include them
     */
    public function format(NameFormatEnum $format): string
    {
        $pattern = str_split($format->value);

        $names = [];
        foreach ($pattern as $symbol) {
            if ($symbol === 'l') {
                $names[] = $this->lastName;
            } elseif ($symbol === 'f') {
                $names[] = $this->firstName;
            } elseif ($symbol === 'm') {
                $names[] = $this->middleName;
            }
        }

        $names = array_filter($names);

        return implode(' ', $names);
    }
}
