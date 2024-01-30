<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Enum\NameFormatEnum;
use App\Exception\Name\EmptyNameException;

final readonly class PersonName
{
    private function __construct(
        private string $lastName,
        private string $firstName,
        private ?string $middleName = null,
    ) {}

    public static function make(
        string $lastName,
        string $firstName,
        ?string $middleName = null,
    ): self {
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

    public function getFirstName(): string
    {
        return $this->firstName;
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
