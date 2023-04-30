<?php

declare(strict_types=1);

namespace App\Enum;

enum VoteEnum: int
{
    case NEGATIVE = -1;
    case ABSTAIN = 0;
    case POSITIVE = 1;
}

