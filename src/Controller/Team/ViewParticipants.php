<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Team;
use App\Feature\Team\Repository\TeamParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/teams/{id}/participants', name: 'app_account_team_participants')]
final class ViewParticipants extends AbstractController
{
    public function __construct(
        private TeamParticipantRepository $teamParticipantRepository,
    ) {}

    public function __invoke(Team $team): Response
    {
        $participants = $this->teamParticipantRepository->findActiveByTeam($team);

        return $this->render('account/team/participants.html.twig', [
            'team' => $team,
            'participants' => $participants,
        ]);
    }
}
