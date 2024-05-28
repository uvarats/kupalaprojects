<?php

declare(strict_types=1);

namespace App\Feature\Team\Interface;

use App\Entity\TeamInvite;
use App\Feature\Team\Dto\InviteIssueResult;
use App\Feature\Team\Dto\IssueInvitesRequest;
use App\Feature\Team\Enum\InviteStateChangeResultEnum;

interface TeamInviteServiceInterface
{
    public function issue(IssueInvitesRequest $request): InviteIssueResult;
    public function revoke(TeamInvite $invite): void;
    public function handleAccept(TeamInvite $invite): InviteStateChangeResultEnum;
    public function handleReject(TeamInvite $invite): InviteStateChangeResultEnum;
}
