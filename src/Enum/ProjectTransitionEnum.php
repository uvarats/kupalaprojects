<?php

declare(strict_types=1);

namespace App\Enum;

enum ProjectTransitionEnum: string
{
    case APPROVE = 'approve';
    case REJECT = 'reject';
    case TO_MODERATION = 'to_moderation';
    case END = 'end';
}
