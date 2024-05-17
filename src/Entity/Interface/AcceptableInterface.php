<?php

declare(strict_types=1);

namespace App\Entity\Interface;

interface AcceptableInterface
{
    public function isApproved(): bool;

    public function isRejected(): bool;

    public function isPending(): bool;

    public function approve(): void;

    public function reject(): void;

    public function retractDecision(): void;
}
