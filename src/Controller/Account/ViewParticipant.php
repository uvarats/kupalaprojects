<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Feature\Participant\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/participant', name: 'app_account_participant_view')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ViewParticipant extends AbstractController
{
    public function __construct(
        private readonly ParticipantRepository $participantRepository,
    ) {}

    public function __invoke(#[CurrentUser] User $user): Response
    {
        $participant = $this->participantRepository->findOneForUser($user);

        return $this->render('account/participant/view.html.twig', [
            'user' => $user,
            'participant' => $participant,
        ]);
    }
}
