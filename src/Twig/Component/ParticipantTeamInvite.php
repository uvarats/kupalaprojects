<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\TeamInvite;
use App\Feature\Team\Enum\InviteStateChangeResultEnum;
use App\Feature\Team\Security\TeamInviteVoter;
use App\Feature\Team\Service\TeamInviteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ParticipantTeamInvite extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public TeamInvite $invite;

    public InviteStateChangeResultEnum $result = InviteStateChangeResultEnum::NO_RESULT;

    public function __construct(
        private readonly TeamInviteService $inviteService,
    ) {}

    #[LiveAction]
    public function accept(): void
    {
        $this->denyAccessUnlessGranted(TeamInviteVoter::IS_RECIPIENT, $this->invite);

        $this->result = $this->inviteService->handleAccept($this->invite);
    }

    #[LiveAction]
    public function reject(): void
    {
        $this->denyAccessUnlessGranted(TeamInviteVoter::IS_RECIPIENT, $this->invite);

        $this->result = $this->inviteService->handleReject($this->invite);
    }
}
