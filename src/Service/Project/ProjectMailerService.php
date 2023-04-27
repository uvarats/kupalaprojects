<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\NewUserProject;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final readonly class ProjectMailerService
{
    public function __construct(
        private string $mailFrom,
        private MailerInterface $mailer,
    ) {
    }

    public function sendNewUserEmail(NewUserProject $newUserProject) {
        $project = $newUserProject->project;
        $user = $newUserProject->userPassword->user;
        $rawPassword = $newUserProject->userPassword->password;

        $message = new TemplatedEmail();
        $recipient = new Address($user->getEmail());
        $message
            ->to($recipient)
            ->subject('Заявка проекта на платформе "Купаловские проекты"')
            ->htmlTemplate('mail/new_project_author.html.twig')
            ->context([
                'project' => $project,
                'user' => $user,
                'rawPassword' => $rawPassword,
            ]);


        $this->mailer->send($message);
    }
}
