<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Entity\Team;
use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Core\ValueObject\Email;
use App\Feature\Participant\ValueObject\ParticipantId;
use App\Feature\Team\Dto\InviteData;
use App\Feature\Team\Dto\IssueInvitesRequest;
use App\Feature\Team\Form\TeamInvitesType;
use App\Feature\Team\Interface\InviteMailerInterface;
use App\Feature\Team\Interface\TeamInviteServiceInterface;
use App\Feature\Team\Security\TeamVoter;
use App\Feature\Team\Service\InviteMailerService;
use App\Feature\Team\ValueObject\TeamId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamInviteForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?InviteData $data = null;

    #[LiveProp]
    public Team $team;

    public function __construct(
        private readonly TeamInviteServiceInterface $inviteService,
        private readonly InviteMailerInterface $mailer,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(TeamInvitesType::class, $this->data);
    }

    #[LiveAction]
    public function save(#[CurrentParticipant] Participant $participant): void
    {
        $this->denyAccessUnlessGranted(TeamVoter::IS_TEAM_OWNER, $this->team);

        $this->submitForm();

        /** @var InviteData $data */
        $data = $this->getForm()->getData();
        $issueRequest = $this->makeIssueRequest($this->team, $participant, $data);
        $issueResult = $this->inviteService->issue($issueRequest);

        $this->formView = null;
        foreach ($issueResult->getErrors() as $error) {
            $this->getForm()->addError(new FormError($error));
        }

        if ($issueResult->isSuccess()) {
            $invites = $issueResult->getIssuedInvites();
            $this->mailer->massSend($invites);
            $this->resetForm();
        }

        if ($issueResult->isInvitesIssued()) {
            $this->emit('invitesIssued');
        }
    }

    private function makeIssueRequest(Team $team, Participant $participant, InviteData $data): IssueInvitesRequest
    {
        $emails = new EmailCollection();
        $rawEmails = $data->getEmails();

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
