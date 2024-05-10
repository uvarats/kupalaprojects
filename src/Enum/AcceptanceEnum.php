<?php

declare(strict_types=1);

namespace App\Enum;

enum AcceptanceEnum: string
{
    case NO_DECISION = 'no_decision';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function fromString(string $decision): AcceptanceEnum
    {
        return match($decision) {
            'approve' => AcceptanceEnum::APPROVED,
            'reject' => AcceptanceEnum::REJECTED,
            default => throw new \InvalidArgumentException('Allowed values: approve and reject'),
        };
    }

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    public function isNoDecision(): bool
    {
        return $this === self::NO_DECISION;
    }
}
