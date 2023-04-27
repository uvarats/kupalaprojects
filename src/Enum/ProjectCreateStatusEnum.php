<?php

declare(strict_types=1);

namespace App\Enum;

enum ProjectCreateStatusEnum: int
{
    case USER_CREATED = 1;
    case ONLY_PROJECT_CREATED = 3;
}

