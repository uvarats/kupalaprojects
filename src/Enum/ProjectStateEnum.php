<?php

declare(strict_types=1);

namespace App\Enum;

enum ProjectStateEnum: string
{
    case UNDER_MODERATION = 'under_moderation';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PRE_VOTING = 'pre_voting';
    case UNDER_VOTING = 'under_voting';
    case ENDED_VOTING = 'ended_voting';
    case ENDED = 'ended';
}
