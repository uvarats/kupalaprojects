<?php

declare(strict_types=1);

namespace App\Feature\Festival\Service;

use App\Entity\FestivalMail;
use App\Enum\FestivalMailPlaceholderEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\String\UnicodeString;

final readonly class FestivalMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
    ) {}

    public function send(FestivalMail $mail): void
    {
        $content = $this->replaceContentPlaceholders($mail);

        $rawRecipients = $mail->getRecipients();
        $recipient = Address::createArray($rawRecipients);

        $rawCc = $mail->getCc();
        $cc = Address::createArray($rawCc);

        $rawBcc = $mail->getBcc();
        $bcc = Address::createArray($rawBcc);

        $email = new Email();
        $email->to(...$recipient)
            ->cc(...$cc)
            ->bcc(...$bcc)
            ->subject($mail->getSubject())
            ->html($content);

        $this->mailer->send($email);

        $mail->setSentAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    private function replaceContentPlaceholders(FestivalMail $mail): string
    {
        $content = new UnicodeString(
            string: $mail->getContent()
        );

        foreach (FestivalMailPlaceholderEnum::cases() as $placeholderEnum) {
            $placeholder = '{{' . $placeholderEnum->value . '}}';

            if ($content->containsAny($placeholder)) {
                $value = $this->getPlaceholderValue($placeholderEnum, $mail);

                $content = $content->replace($placeholder, $value);
            }
        }

        return $content->toString();
    }

    private function getPlaceholderValue(
        FestivalMailPlaceholderEnum $placeholderEnum,
        FestivalMail $mail,
    ): string {
        $festival = $mail->getFestival();

        return match ($placeholderEnum) {
            FestivalMailPlaceholderEnum::FESTIVAL_NAME => $festival->getName(),
        };
    }

}
