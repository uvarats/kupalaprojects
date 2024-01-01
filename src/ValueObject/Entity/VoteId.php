<?php

declare(strict_types=1);

namespace App\ValueObject\Entity;

use Symfony\Component\Uid\Uuid;

final readonly class VoteId implements \JsonSerializable, \Stringable
{
    private function __construct(private Uuid $id) {}

    public static function make(?Uuid $uuid): ?VoteId
    {
        if ($uuid === null) {
            return null;
        }

        return new self($uuid);
    }

    public function getValue(): Uuid
    {
        return $this->id;
    }


    public function __toString(): string
    {
        return $this->id->toRfc4122();
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->id->toRfc4122();
    }

    public function equals(VoteId $id): bool
    {
        $comparedTo = $id->id;

        return $this->id->equals($comparedTo);
    }
}
