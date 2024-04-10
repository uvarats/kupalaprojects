<?php

declare(strict_types=1);

namespace App\Dto;

use App\Validator\DateRangeValidator;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Callback([DateRangeValidator::class, 'validate'])]
final class EventDatesData
{
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $startsAt = null;

    #[Assert\NotBlank]
    private ?\DateTimeImmutable $endsAt = null;

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeImmutable $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeImmutable $endsAt): void
    {
        $this->endsAt = $endsAt;
    }


}
