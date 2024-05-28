<?php

declare(strict_types=1);

namespace App\Feature\Team\Service;

use App\Entity\TeamInvite;
use App\Feature\Team\Collection\TeamInviteCollection;
use App\Feature\Team\Interface\InviteMailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class InviteMailerService implements InviteMailerInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
    ) {}

    #[\Override]
    public function sendInviteMail(TeamInvite $invite): void
    {
        $recipient = $invite->getRecipient();
        $toEmail = $recipient->getEmail();

        $mail = new TemplatedEmail();
        $mail->to($toEmail)
            ->subject($this->translator->trans('team.invite.email.subject'))
            ->htmlTemplate('mail/team_invite.html.twig')
            ->context([
                'recipient' => $recipient,
                'team' => $invite->getTeam(),
                'issuer' => $invite->getIssuer(),
            ]);

        $this->mailer->send($mail);
    }

    #[\Override]
    public function massSend(TeamInviteCollection $inviteCollection): void
    {
        foreach ($inviteCollection as $invite) {
            $this->sendInviteMail($invite);
        }
    }
}
