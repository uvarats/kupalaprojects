<?php

declare(strict_types=1);

namespace App\Enum;

enum VotingStateEnum: string
{
    case INACTIVE = 'inactive';
    case PRE_VOTING = 'pre_voting';
    case IN_PROGRESS = 'in_progress';
    case ENDED = 'ended';
}
