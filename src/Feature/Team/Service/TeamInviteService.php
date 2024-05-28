<?php

declare(strict_types=1);

namespace App\Feature\Team\Service;

use App\Entity\Participant;
use App\Entity\Team;
use App\Entity\TeamInvite;
use App\Feature\Participant\Collection\ParticipantCollection;
use App\Feature\Participant\Repository\ParticipantRepository;
use App\Feature\Team\Collection\TeamInviteCollection;
use App\Feature\Team\Dto\InviteIssueResult;
use App\Feature\Team\Dto\IssueInvitesRequest;
use App\Feature\Team\Enum\InviteStateChangeResultEnum;
use App\Feature\Team\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Feature\Team\Interface\TeamInviteServiceInterface;

final readonly class TeamInviteService implements TeamInviteServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teamRepository,
        private ParticipantRepository $participantRepository,
        private TranslatorInterface $translator,
    ) {}

    #[\Override]
    public function issue(IssueInvitesRequest $request): InviteIssueResult
    {
        $team = $this->getTeamFromIssueRequest($request);
        $issuer = $this->getIssuer($request);
        $recipients = $this->getRecipients($request);

        $invites = new TeamInviteCollection();
        $errorMessages = [];
        foreach ($recipients as $recipient) {
            // todo: come up with better approach. maybe it is possible to pre-catch this errors on validation stage?
            if ($issuer === $recipient) {
                $errorMessages[] = $this->translator->trans('team.invite.selfInviteError');

                continue;
            }

            if ($team->hasParticipant($recipient)) {
                $errorMessages[] = $this->buildAlreadyExistingParticipantMessage($recipient);

                continue;
            }

            $invite = new TeamInvite($team, $issuer, $recipient);
            $invites[] = $invite;

            $this->entityManager->persist($invite);
        }

        $this->entityManager->flush();

        return InviteIssueResult::create($invites, $errorMessages);
    }

    private function getTeamFromIssueRequest(IssueInvitesRequest $request): Team
    {
        $teamId = $request->getTeamId();

        $team = $this->teamRepository->findById($teamId);
        if ($team === null) {
            throw new \LogicException('Attempt to issue invites for non-existing team');
        }

        return $team;
    }

    private function getIssuer(IssueInvitesRequest $request): ?Participant
    {
        $issuerId = $request->getIssuerId();

        return $this->participantRepository->findById($issuerId);
    }

    private function getRecipients(IssueInvitesRequest $request): ParticipantCollection
    {
        $recipientsEmails = $request->getEmails();

        return $this->participantRepository->findByEmails($recipientsEmails);
    }

    private function buildAlreadyExistingParticipantMessage(Participant $participant): string
    {
        return $this->translator->trans(
            'team.invite.existingParticipantError',
            ['%email%' => $participant->getEmail()]
        );
    }

    #[\Override]
    public function revoke(TeamInvite $invite): void
    {
        $invite->revoke();

        $this->entityManager->flush();
    }

    #[\Override]
    public function handleAccept(TeamInvite $invite): InviteStateChangeResultEnum
    {
        if (!$invite->isPending()) {
            return InviteStateChangeResultEnum::INVALID_INVITE_STATE;
        }

        $invite->accept();

        $this->entityManager->flush();

        return InviteStateChangeResultEnum::SUCCESS;
    }

    #[\Override]
    public function handleReject(TeamInvite $invite): InviteStateChangeResultEnum
    {
        if (!$invite->isPending()) {
            return InviteStateChangeResultEnum::INVALID_INVITE_STATE;
        }

        $invite->reject();

        $this->entityManager->flush();

        return InviteStateChangeResultEnum::SUCCESS;
    }
}
