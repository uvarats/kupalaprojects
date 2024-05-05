<?php

declare(strict_types=1);

namespace App\Feature\Team\Enum;

enum InviteStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case REVOKED = 'revoked';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}

