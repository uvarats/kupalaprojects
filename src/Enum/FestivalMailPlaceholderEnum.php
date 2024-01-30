<?php

declare(strict_types=1);

namespace App\Enum;

enum FestivalMailPlaceholderEnum: string
{
    case FESTIVAL_NAME = 'festival.name';
    case USER_NAME = 'user.name';
}
