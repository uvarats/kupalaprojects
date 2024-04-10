<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\Form\Participant\ParticipantData;
use App\Entity\Participant;
use App\Entity\Project;
use App\Enum\AcceptanceEnum;
use App\ValueObject\PersonName;
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

    public function handleParticipantRegistration(ParticipantData $participantData, Project $project): Participant
    {
        $name = PersonName::make(
            lastName: $participantData->getLastName(),
            firstName: $participantData->getFirstName(),
            middleName: $participantData->getMiddleName(),
        );

        $participant = Participant::make(
            project: $project,
            name: $name,
            educationEstablishment: $participantData->getEducationEstablishment(),
            email: $participantData->getEmail(),
        );

        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        $this->sendMail($participant);

        return $participant;
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
