<?php

declare(strict_types=1);

namespace App\Controller\TeamInvite;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Feature\Team\Repository\TeamInviteRepository;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/team/invites', name: 'app_account_my_team_invites')]
#[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
final class ViewParticipantInvites extends AbstractController
{
    public function __construct(
        private readonly TeamInviteRepository $inviteRepository,
    ) {}

    public function __invoke(#[CurrentParticipant] Participant $participant): Response
    {
        $invites = $this->inviteRepository->findAllPendingByRecipient($participant);

        return $this->render('account/team/my-invites.html.twig', [
            'invites' => $invites,
        ]);
    }
}
