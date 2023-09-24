<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Participant;
use App\Enum\AcceptanceEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class ParticipantService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
    ) {}

    public function handleParticipantRegistration(Participant $participant): void
    {
        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        $this->sendMail($participant);
    }

    public function makeParticipantDecision(Participant $participant, string $decision): void
    {
        $currentAcceptance = $participant->getAcceptance();
        if ($currentAcceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $newAcceptance = AcceptanceEnum::fromString($decision);
        $participant->setAcceptance($newAcceptance);

        $this->entityManager->flush();
    }

    private function sendMail(Participant $participant): void
    {
        $mail = new TemplatedEmail();
        $mail->to($participant->getEmail())
            ->subject($this->translator->trans('participant.email.subject'))
            ->htmlTemplate('mail/participant_registration.html.twig')
            ->context([
                'participant' => $participant,
            ]);

        $this->mailer->send($mail);
    }
}
