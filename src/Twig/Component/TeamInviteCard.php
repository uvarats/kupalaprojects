<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\TeamInvite;
use App\Feature\Team\Interface\TeamInviteServiceInterface;
use App\Feature\Team\Security\TeamVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamInviteCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public TeamInvite $invite;

    #[LiveAction]
    public function revoke(TeamInviteServiceInterface $inviteService): void
    {
        $team = $this->invite->getTeam();
        $this->denyAccessUnlessGranted(TeamVoter::IS_TEAM_OWNER, $team);

        $inviteService->revoke($this->invite);
    }
}
