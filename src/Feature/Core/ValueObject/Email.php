<?php

declare(strict_types=1);

namespace App\Feature\Core\ValueObject;

final readonly class Email implements \Stringable, \JsonSerializable
{
    private function __construct(private string $email) {}

    public static function fromString(string $email): Email
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException('Invalid email');
        }

        return new self(email: $email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->toString();
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
