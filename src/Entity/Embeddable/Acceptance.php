<?php

declare(strict_types=1);

namespace App\Entity\Embeddable;

use App\Entity\Interface\AcceptableInterface;
use App\Enum\AcceptanceEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Acceptance implements AcceptableInterface
{
    #[ORM\Column(enumType: AcceptanceEnum::class)]
    private AcceptanceEnum $acceptance;

    public function __construct(AcceptanceEnum $acceptance = AcceptanceEnum::NO_DECISION)
    {
        $this->acceptance = $acceptance;
    }

    public function getAcceptance(): AcceptanceEnum
    {
        return $this->acceptance;
    }

    public function isApproved(): bool
    {
        return $this->acceptance === AcceptanceEnum::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->acceptance === AcceptanceEnum::REJECTED;
    }

    public function isWaitingForDecision(): bool
    {
        return $this->acceptance === AcceptanceEnum::NO_DECISION;
    }

    public function approve(): void
    {
        if ($this->acceptance === AcceptanceEnum::REJECTED) {
            throw new \LogicException('Can not approve already rejected entity');
        }

        $this->acceptance = AcceptanceEnum::APPROVED;
    }

    public function reject(): void
    {
        if ($this->acceptance == AcceptanceEnum::APPROVED) {
            throw new \LogicException('Can not reject already approved entity');
        }

        $this->acceptance = AcceptanceEnum::REJECTED;
    }

    public function stage(): void
    {
        $this->acceptance = AcceptanceEnum::NO_DECISION;
    }
}
