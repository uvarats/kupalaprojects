<?php

declare(strict_types=1);

namespace App\Feature\Festival\Dto;

use App\Entity\Festival;
use App\Entity\User;

final readonly class CreateFestivalMail
{
    public function __construct(
        private Festival $festival,
        private User $author,
        private string $subject,
        private string $content,
        private array $recipients,
        private array $bcc = [],
        private array $cc = [],
    ) {}

    public function getFestival(): Festival
    {
        return $this->festival;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getCc(): array
    {
        return $this->cc;
    }
}
