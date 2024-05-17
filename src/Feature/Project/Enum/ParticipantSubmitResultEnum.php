<?php

declare(strict_types=1);

namespace App\Feature\Project\Enum;

enum ParticipantSubmitResultEnum
{
    case SUCCESS;
    case PARTICIPANT_ALREADY_SUBMITTED;
}

