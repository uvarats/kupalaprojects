<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Feature\Account\Dto\CreateTeamRequest;
use App\Feature\Account\Dto\TeamData;
use App\Feature\Account\Form\TeamType;
use App\Feature\Account\Service\AccountTeamService;
use App\Security\Voter\ParticipantVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/teams/create', name: 'app_account_team_create')]
#[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
final class CreateTeam extends AbstractController
{
    public function __construct(
        private readonly AccountTeamService $teamService,
    ) {}

    // todo: participant resolver
    public function __invoke(Request $request, #[CurrentParticipant] Participant $participant): Response
    {
        $data = new TeamData();
        $form = $this->createForm(TeamType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createRequest = CreateTeamRequest::make(
                name: $data->getName(),
                creator: $participant,
            );

            $this->teamService->create($createRequest);

            return $this->redirectToRoute('app_account_teams');
        }

        return $this->render('account/team/create.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }
}
