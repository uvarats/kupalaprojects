<?php

declare(strict_types=1);

namespace App\Feature\Team\Interface;

use App\Entity\TeamInvite;
use App\Feature\Team\Collection\TeamInviteCollection;

interface InviteMailerInterface
{
    public function massSend(TeamInviteCollection $inviteCollection): void;
    public function sendInviteMail(TeamInvite $invite): void;
}
