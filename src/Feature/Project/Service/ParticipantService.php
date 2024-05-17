<?php

declare(strict_types=1);

namespace App\Feature\Project\Service;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\ProjectParticipant;
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

    public function retractParticipantApplication(Project $project, Participant $participant): void
    {
        $project->retractParticipant($participant);

        $this->entityManager->flush();
    }

    public function approve(ProjectParticipant $participant): void
    {
        $participant->approve();

        $this->entityManager->flush();
    }

    public function reject(ProjectParticipant $participant): void
    {
        $participant->reject();

        $this->entityManager->flush();
    }

    public function retractDecision(ProjectParticipant $participant): void
    {
        $participant->retractDecision();

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
                'projectParticipant' => $projectParticipant,
            ]);

        $this->mailer->send($mail);
    }
}
