<?php

declare(strict_types=1);

namespace App\Feature\Festival\Dto;

final class FestivalMailData
{
    private string $subject = '';
    private string $content = '';
    private array $recipients = [];

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }
}
