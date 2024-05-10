<?php

declare(strict_types=1);

namespace App\Feature\Team\Enum;

enum InviteStateChangeResultEnum
{
    case NO_RESULT;
    case SUCCESS;
    case INVALID_INVITE_STATE;

    public function isPending(): bool
    {
        return $this === self::NO_RESULT;
    }

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isInvalidInviteState(): bool
    {
        return $this === self::INVALID_INVITE_STATE;
    }

}

