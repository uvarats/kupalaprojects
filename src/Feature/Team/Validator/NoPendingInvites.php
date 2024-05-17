<?php

declare(strict_types=1);

namespace App\Feature\Team\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class NoPendingInvites extends Constraint
{
    public string $message = 'Invite for email {{ email }} already sent and waiting for decision.';
}
