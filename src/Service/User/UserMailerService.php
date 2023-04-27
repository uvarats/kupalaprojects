<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Dto\UserPassword;
use Symfony\Component\Mailer\MailerInterface;

final readonly class UserMailerService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function sendToNewUser(UserPassword $userPassword, string $loginLink) {

    }
}
