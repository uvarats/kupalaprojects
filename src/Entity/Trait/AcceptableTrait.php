<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Enum\AcceptanceEnum;
use Doctrine\ORM\Mapping as ORM;

trait AcceptableTrait
{
    #[ORM\Column(length: 255, enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION;

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function approve(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $this->acceptance = AcceptanceEnum::APPROVED;
    }

    public function reject(): void
    {
        if ($this->acceptance !== AcceptanceEnum::NO_DECISION) {
            return;
        }

        $this->acceptance = AcceptanceEnum::REJECTED;
    }

    public function isRejected(): bool
    {
        return $this->acceptance === AcceptanceEnum::REJECTED;
    }

    public function isPending(): bool
    {
        return $this->acceptance === AcceptanceEnum::NO_DECISION;
    }

    public function isApproved(): bool
    {
        return $this->acceptance === AcceptanceEnum::APPROVED;
    }

    public function retractDecision(): void
    {
        $this->acceptance = AcceptanceEnum::NO_DECISION;
    }
}
