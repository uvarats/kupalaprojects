<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Team;
use App\Feature\Team\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamArchiveConfirmation extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Team $team;

    #[LiveAction]
    public function confirm(TeamService $teamService): Response
    {
        $teamService->archive($this->team);

        return $this->redirectToRoute('app_account_teams');
    }

    #[LiveAction]
    public function cancel(): Response
    {
        return $this->redirectToRoute('app_account_teams');
    }
}
