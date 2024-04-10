<?php

declare(strict_types=1);

namespace App\ValueObject\Entity;

use Symfony\Component\Uid\Uuid;

final readonly class FestivalId implements \JsonSerializable, \Stringable
{
    private function __construct(
        private Uuid $id,
    ) {}

    public static function fromString(string $id): FestivalId
    {
        if (!Uuid::isValid($id)) {
            throw new \DomainException('Festival ID must be valid UUID');
        }

        return new self(
            id: Uuid::fromString($id),
        );
    }

    public function toString(): string
    {
        return (string)$this->id;
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
