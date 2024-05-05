<?php

declare(strict_types=1);

namespace App\Feature\Team\ValueObject;

use Symfony\Component\Uid\Uuid;

final readonly class TeamId implements \JsonSerializable, \Stringable
{
    private function __construct(
        private Uuid $id,
    ) {}

    public static function fromString(string $id): TeamId
    {
        if (!Uuid::isValid($id)) {
            throw new \DomainException('Team ID must be valid uuid');
        }

        return new self(
            id: Uuid::fromString($id),
        );
    }

    public function toString(): string
    {
        return $this->id->toRfc4122();
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
