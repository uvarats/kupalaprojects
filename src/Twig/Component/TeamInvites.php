<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Team;
use App\Feature\Team\Collection\TeamInviteCollection;
use App\Feature\Team\Repository\TeamInviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class TeamInvites extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Team $team;

    public function __construct(
        private readonly TeamInviteRepository $inviteRepository,
    ) {}

    #[LiveListener('invitesIssued')]
    public function refresh(): void
    {
    }

    #[ExposeInTemplate]
    public function getInvites(): TeamInviteCollection
    {
        return $this->inviteRepository->findAllByTeam($this->team);
    }
}
