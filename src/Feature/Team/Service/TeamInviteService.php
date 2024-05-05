<?php

declare(strict_types=1);

namespace App\Feature\Team\Service;

use App\Entity\TeamInvite;
use App\Feature\Participant\Repository\ParticipantRepository;
use App\Feature\Team\Collection\TeamInviteCollection;
use App\Feature\Team\Dto\IssueInvitesRequest;
use App\Feature\Team\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TeamInviteService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teamRepository,
        private ParticipantRepository $participantRepository,
    ) {}

    public function issue(IssueInvitesRequest $request): void
    {
        $teamId = $request->getTeamId();
        $team = $this->teamRepository->findById($teamId);

        if ($team === null) {
            throw new \LogicException('Attempt to issue invites for non-existing team');
        }

        $issuerId = $request->getIssuerId();
        $issuer = $this->participantRepository->findById($issuerId);

        $recipientsEmails = $request->getEmails();
        $recipients = $this->participantRepository->findByEmails($recipientsEmails);

        $invites = new TeamInviteCollection();
        foreach ($recipients as $recipient) {
            $invite = new TeamInvite($team, $issuer, $recipient);
            $invites[] = $invite;

            $this->entityManager->persist($invite);
        }

        $this->entityManager->flush();
    }

    public function revoke(TeamInvite $invite): void
    {
        $invite->revoke();

        $this->entityManager->flush();
    }
}
