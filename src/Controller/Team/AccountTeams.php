<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Repository\TeamRepository;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/teams', name: 'app_account_teams')]
#[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
final class AccountTeams extends AbstractController
{
    public function __construct(private readonly TeamRepository $teamRepository) {}

    public function __invoke(#[CurrentParticipant] Participant $participant): Response
    {
        $createdTeams = $this->teamRepository->findCreatedByParticipant($participant);
        $participatedTeams = $this->teamRepository->findParticipatedByParticipant($participant);

        return $this->render('account/team/index.html.twig', [
            'createdTeams' => $createdTeams,
            'participatedTeams' => $participatedTeams,
        ]);
    }
}
