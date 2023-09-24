<?php

declare(strict_types=1);

namespace App\Service\Festival;

use App\Entity\FestivalMail;
use App\Entity\Project;
use App\Enum\FestivalMailPlaceholderEnum;
use App\Repository\ProjectRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\String\UnicodeString;

final readonly class FestivalMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private ProjectRepository $projectRepository,
    ) {}

    public function send(FestivalMail $mail): void
    {
        $festival = $mail->getFestival();
        $projects = $this->projectRepository->getFestivalProjects($festival);

        foreach ($projects as $project) {
            $author = $project->getAuthor();
            $user = $author->getUserEntity();

            $content = $this->replaceContentPlaceholders($mail, $project);

            $email = new Email();
            $email->to($user->getEmail())
                ->subject($mail->getSubject())
                ->html($content);

            $this->mailer->send($email);
        }
    }

    private function replaceContentPlaceholders(FestivalMail $mail, Project $project): string
    {
        $content = new UnicodeString(
            string: $mail->getContent()
        );

        foreach (FestivalMailPlaceholderEnum::cases() as $placeholderEnum) {
            $placeholder = '{{' . $placeholderEnum->value . '}}';

            if ($content->containsAny($placeholder)) {
                $value = $this->getPlaceholderValue($placeholderEnum, $mail, $project);

                $content = $content->replace($placeholder, $value);
            }
        }

        return $content->toString();
    }

    private function getPlaceholderValue(
        FestivalMailPlaceholderEnum $placeholderEnum,
        FestivalMail $mail,
        Project $project,
    ): string {
        $festival = $mail->getFestival();
        $author = $project->getAuthor();
        $user = $author->getUserEntity();

        return match ($placeholderEnum) {
            FestivalMailPlaceholderEnum::FESTIVAL_NAME => $festival->getName(),
            FestivalMailPlaceholderEnum::USER_NAME => $user->getFirstAndMiddleName(),
        };
    }

}
