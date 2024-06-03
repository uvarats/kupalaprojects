<?php

declare(strict_types=1);

namespace App\Feature\Import\Enum;

enum ImportErrorReasonEnum
{
    case COLUMN_COUNT_MISMATCH;
    case INVALID_DATA;
}
