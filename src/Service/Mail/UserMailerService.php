<?php

declare(strict_types=1);

namespace App\Service\Mail;

use App\Feature\Account\Dto\NewUserMailData;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final readonly class UserMailerService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function sendToNewUser(NewUserMailData $authorDto): void
    {
        $user = $authorDto->user;
        $rawPassword = $authorDto->password;
        $loginLink = $authorDto->loginLink;

        $letter = new TemplatedEmail();
        $recipient = new Address($user->getEmail());
        $letter
            ->to($recipient)
            ->subject('Регистрация на платформе "Купаловские проекты"')
            ->htmlTemplate('mail/new_user.html.twig')
            ->context([
                'user' => $user,
                'rawPassword' => $rawPassword,
                'loginLink' => $loginLink,
            ]);

        $this->mailer->send($letter);
    }
}
