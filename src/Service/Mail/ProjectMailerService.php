<?php

declare(strict_types=1);

namespace App\Service\Mail;

use Symfony\Component\Mailer\MailerInterface;

final readonly class ProjectMailerService
{
    public function __construct(
        private string $mailFrom,
        private MailerInterface $mailer,
    ) {
    }

    public function sendNewUserEmail() {

    }
}
