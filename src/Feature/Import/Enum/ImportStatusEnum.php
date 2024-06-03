<?php

declare(strict_types=1);

namespace App\Feature\Import\Enum;

enum ImportStatusEnum: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case COMPLETE = 'complete';
    case ERROR = 'error';
}

