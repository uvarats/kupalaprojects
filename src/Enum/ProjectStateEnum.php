<?php

declare(strict_types=1);

namespace App\Enum;

enum ProjectStateEnum: string
{
    case UNDER_MODERATION = 'under_moderation';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case UNDER_VOTING = 'under_voting';
    case AFTER_VOTING = 'after_voting';
    case ENDED = 'ended';
}

