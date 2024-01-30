<?php

declare(strict_types=1);

namespace App\Entity\Interface;

use App\Enum\AcceptanceEnum;

interface AcceptableInterface
{
    public function getAcceptance(): AcceptanceEnum;

    public function isApproved(): bool;

    public function isRejected(): bool;

    public function isWaitingForDecision(): bool;

    public function approve(): void;

    public function reject(): void;

    public function stage(): void;
}
