<?php

declare(strict_types=1);

namespace App\ValueObject\Entity;

use Symfony\Component\Uid\Uuid;

final readonly class ProjectId implements \Stringable, \JsonSerializable
{
    private function __construct(
        private Uuid $id,
    ) {}

    public static function fromString(string $id): ProjectId
    {
        if (!Uuid::isValid($id)) {
            throw new \DomainException('Project id must be valid uuid');
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
