<?php

declare(strict_types=1);

namespace App\Feature\Project\Service;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\ProjectParticipant;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Enum\ParticipantSubmitResultEnum;
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

    public function submitParticipant(Project $project, Participant $participant): ParticipantSubmitResultEnum
    {
        if ($project->hasIndividualParticipant($participant)) {
            return ParticipantSubmitResultEnum::PARTICIPANT_ALREADY_SUBMITTED;
        }

        $projectParticipant = $project->submitParticipant($participant);
        $this->entityManager->flush();

        $this->sendMail($projectParticipant);

        return ParticipantSubmitResultEnum::SUCCESS;
    }

    public function makeParticipantDecision(ProjectParticipant $participant, string $decision): void
    {
        $currentAcceptance = $participant->getAcceptance();
        if ($currentAcceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $newAcceptance = AcceptanceEnum::fromString($decision);
        $participant->setAcceptance($newAcceptance);

        $this->entityManager->flush();
    }

    private function sendMail(ProjectParticipant $projectParticipant): void
    {
        $participant = $projectParticipant->getParticipant();

        $mail = new TemplatedEmail();
        $mail->to($participant->getEmail())
            ->subject($this->translator->trans('participant.email.subject'))
            ->htmlTemplate('mail/participant_registration.html.twig')
            ->context([
                'participant' => $projectParticipant,
            ]);

        $this->mailer->send($mail);
    }
}
