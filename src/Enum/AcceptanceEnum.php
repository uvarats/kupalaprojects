<?php

declare(strict_types=1);

namespace App\Enum;

enum AcceptanceEnum: int
{
    case NO_DECISION = 0;
    case APPROVED = 1;
    case REJECTED = 2;

    public static function fromString(string $decision): AcceptanceEnum
    {
        return match($decision) {
            'approve' => AcceptanceEnum::APPROVED,
            'reject' => AcceptanceEnum::REJECTED,
            default => throw new \InvalidArgumentException('Allowed values: approve and reject'),
        };
    }
}
