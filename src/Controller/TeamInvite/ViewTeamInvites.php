<?php

declare(strict_types=1);

namespace App\Controller\TeamInvite;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Entity\Team;
use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Core\ValueObject\Email;
use App\Feature\Participant\ValueObject\ParticipantId;
use App\Feature\Team\Dto\InviteData;
use App\Feature\Team\Dto\IssueInvitesRequest;
use App\Feature\Team\Form\TeamInvitesType;
use App\Feature\Team\Repository\TeamInviteRepository;
use App\Feature\Team\Security\TeamVoter;
use App\Feature\Team\Service\TeamInviteService;
use App\Feature\Team\ValueObject\TeamId;
use App\ValueResolver\ParticipantResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/team/{id}/invites', name: 'app_account_team_invites')]
final class ViewTeamInvites extends AbstractController
{
    public function __construct(
        private readonly TeamInviteRepository $inviteRepository,
        private readonly TeamInviteService $inviteService,
    ) {}
    public function __invoke(
        Team $team,
        #[CurrentParticipant]
        #[ValueResolver(ParticipantResolver::class)]
        Participant $participant,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::IS_TEAM_OWNER, $team);

        $inviteData = new InviteData();
        $form = $this->createForm(TeamInvitesType::class, $inviteData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inviteIssueRequest = $this->makeIssueRequest($team, $participant, $inviteData);

            $this->inviteService->issue($inviteIssueRequest);

            $inviteData = new InviteData();
            $form = $this->createForm(TeamInvitesType::class, $inviteData);
        }

        return $this->render('account/team/invites.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
            'invites' => $this->inviteRepository->findAllByTeam($team),
        ]);
    }

    private function makeIssueRequest(Team $team, Participant $participant, InviteData $data): IssueInvitesRequest
    {
        $emails = new EmailCollection();
        $rawEmails = explode(',', $data->getEmails());

        foreach ($rawEmails as $rawEmail) {
            $emails[] = Email::fromString($rawEmail);
        }

        $teamId = $team->getId()->toRfc4122();
        $participantId = $participant->getId()->toRfc4122();

        return IssueInvitesRequest::make(
            emails: $emails,
            teamId: TeamId::fromString($teamId),
            issuerId: ParticipantId::fromString($participantId),
        );
    }
}
