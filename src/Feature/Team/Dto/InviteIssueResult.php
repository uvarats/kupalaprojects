<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\Feature\Team\Collection\TeamInviteCollection;

final class InviteIssueResult
{
    private function __construct(
        private readonly TeamInviteCollection $issuedInvites,
        private readonly array $errors = [],
    ) {}

    public static function create(TeamInviteCollection $issuedInvites, array $errors = []): self
    {
        return new self($issuedInvites, $errors);
    }

    public function isSuccess(): bool
    {
        return $this->errors === [];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getIssuedInvites(): TeamInviteCollection
    {
        return $this->issuedInvites;
    }

    public function isInvitesIssued(): bool
    {
        return !$this->issuedInvites->isEmpty();
    }
}
