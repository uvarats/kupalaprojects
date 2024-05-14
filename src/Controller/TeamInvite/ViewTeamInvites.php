<?php

declare(strict_types=1);

namespace App\Controller\TeamInvite;

use App\Entity\Team;
use App\Feature\Team\Security\TeamVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/team/{id}/invites', name: 'app_account_team_invites')]
final class ViewTeamInvites extends AbstractController
{
    public function __invoke(
        Team $team,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::IS_TEAM_OWNER, $team);

        return $this->render('account/team/invites.html.twig', [
            'team' => $team,
        ]);
    }
}
