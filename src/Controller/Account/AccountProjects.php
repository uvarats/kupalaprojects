<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/participant/projects', name: 'app_account_participant_projects')]
#[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
final class AccountProjects extends AbstractController
{
    public function __construct(
        private readonly ProjectParticipantRepository $participantRepository,
    ) {}
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $individualProjects = $this->participantRepository->findAllForUser($user);

        return $this->render('account/participant/projects.html.twig', [
            'individualProjects' => $individualProjects,
            'teamParticipants' => '', // todo
        ]);
    }
}
