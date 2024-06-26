<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Feature\Festival\Repository\FestivalMailRepository;
use App\Feature\Festival\Service\FestivalMailer;
use App\Message\SendFestivalMail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendFestivalMailHandler
{
    public function __construct(
        private FestivalMailer $mailer,
        private FestivalMailRepository $mailRepository,
    ) {}

    public function __invoke(SendFestivalMail $message): void
    {
        $mail = $this->mailRepository->getMail(
            id: $message->getMailId()
        );

        $this->mailer->send($mail);
    }
}
