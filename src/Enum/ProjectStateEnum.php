<?php

declare(strict_types=1);

namespace App\Enum;

use App\Entity\Voting;

/**
 * Voting states deprecated in favor of using Voting entity states.
 *
 * @see Voting
 * @see VotingStateEnum
 */
enum ProjectStateEnum: string
{
    case UNDER_MODERATION = 'under_moderation';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    /** @deprecated */
    case PRE_VOTING = 'pre_voting';
    /** @deprecated */
    case UNDER_VOTING = 'under_voting';
    /** @deprecated */
    case ENDED_VOTING = 'ended_voting';
    case ENDED = 'ended';
}
