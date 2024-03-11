<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\FestivalMail;

final readonly class SendFestivalMail
{
    public function __construct(
        private string $mailId
    ) {}

    public static function fromMail(FestivalMail $mail): SendFestivalMail
    {
        return new self(
            mailId: (string)$mail->getId(),
        );
    }

    public function getMailId(): string
    {
        return $this->mailId;
    }

}
