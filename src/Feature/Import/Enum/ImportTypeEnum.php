<?php

declare(strict_types=1);

namespace App\Feature\Import\Enum;

enum ImportTypeEnum: string
{
    case PARTICIPANT = 'participant';
    case UNKNOWN = 'unknown';
}

