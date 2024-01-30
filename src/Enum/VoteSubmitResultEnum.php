<?php

declare(strict_types=1);

namespace App\Enum;

enum VoteSubmitResultEnum: int
{
    case VOTE_ACCEPTED = 1;
    case ALREADY_VOTED = 2;
}

