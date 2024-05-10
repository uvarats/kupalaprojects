<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

final class InviteIssueResult
{
    private function __construct(
        private array $errors = [],
    ) {}

    public static function create(): self
    {
        return new self();
    }

    public function isSuccess(): bool
    {
        return $this->errors === [];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }
}
