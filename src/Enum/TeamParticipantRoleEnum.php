<?php

declare(strict_types=1);

namespace App\Enum;

enum TeamParticipantRoleEnum: string
{
    case CREATOR = 'creator';
    case GENERAL_PARTICIPANT = 'general_participant';
}

