<?php

declare(strict_types=1);

namespace App\Enum;

enum ProjectTransitionEnum: string
{
    case APPROVE = 'approve';
    case REJECT = 'reject';
    case CANCEL_REJECTION = 'cancel_rejection';
    case CANCEL_APPROVAL = 'cancel_approval';
    /** @deprecated */
    case START_PRE_VOTING = 'start_pre_voting';
    /** @deprecated */
    case CANCEL_PRE_VOTING = 'cancel_pre_voting';
    /** @deprecated */
    case START_VOTING = 'start_voting';
    /** @deprecated */
    case CANCEL_VOTING = 'cancel_voting';
    /** @deprecated */
    case END_VOTING = 'end_voting';
    case END = 'end';
}
