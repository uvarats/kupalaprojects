<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\ProjectTeam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamModerationCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public ProjectTeam $team;
}
