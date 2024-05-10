<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Team;
use App\Entity\TeamInvite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamInvites extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Team $team;

    /**
     * @var TeamInvite[]
     */
    #[LiveProp]
    public array $invites = [];

    public function onNewInvite() {
    }
}
